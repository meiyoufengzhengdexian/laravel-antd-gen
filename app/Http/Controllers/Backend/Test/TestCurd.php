<?php
namespace App\Http\Controllers\Backend\Test;

use App\Http\Controllers\Backend\BackendException;
use App\Http\Controllers\Backend\BackendPermissionException;
use App\Http\Controllers\Backend\BackendRequest;
use App\Http\Controllers\Backend\BackendResource;
use App\Http\Controllers\Backend\Test\Request\TestCreateRequest;
use App\Http\Controllers\Backend\Test\Request\TestDestroyRequest;
use App\Http\Controllers\Backend\Test\Request\TestIndexRequest;
use App\Http\Controllers\Backend\Test\Request\TestShowRequest;
use App\Http\Controllers\Backend\Test\Request\TestStoreRequest;
use App\Http\Controllers\Backend\Test\Request\TestUpdateRequest;
use App\Http\Controllers\Backend\Lib\AuthResourceTrait;
use App\Http\Controllers\Backend\Lib\ConfigTrait;
use App\Http\Controllers\Backend\Lib\ToolTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait TestCurd
{
    protected $entry;

    use BackendResource;
    use ConfigTrait;
    use ToolTrait;
    use AuthResourceTrait;
    protected $errorMessage = "";

    private function authRange(Authenticatable $admin, Builder $query)
    {
        // $query->where('admin_id', $admin->id);
    }

    private function checkResourcePermission($admin, TestModel $TestModel)
    {
        return true;
    }


    /**
     * @param  TestIndexRequest $request
     * @return  \App\Http\Resources\SuccessResource
     * @throws  BackendException
     */
    public function index(TestIndexRequest $request)
    {
        $indexConfig = $this->getConfig('Test.PageConfig.Index');
        $prePage = $request->input('perPage',
            Arr::get($indexConfig, 'perPage',
                config('backend.defaultPerPage', 20)));

        $query = TestModel::query();

        $this->withRefData($query, $request, $indexConfig);
        $this->authRange($this->getNowAdmin(), $query);

        $this->search($query, $request, $indexConfig);
        $this->order($query, $request, $indexConfig);

        $columnConfig = $this->getConfig('Test.Column');

        $config = self::mergerConfig($columnConfig, $indexConfig);

        $tableColumns = array_filter($config['fields'], function($item){
            return !isset($item['refMethod']);
        });

        $field = array_column($tableColumns, 'name');
        $list = $query->select($field)->paginate($prePage);

        foreach($list as $item) {
            foreach($config['fields'] as $field){
                if(!isset($field['method'])){
                    continue;
                }
                if(!strpos($field['method'], '@')){
                    continue;
                }

                $method = explode('@', $field['method']);
                $object = app(__NAMESPACE__.'\\'.$method[0]);
                if($field['refMethod']){
                    $formatValue = $object->{$method[1]}($item->{$field['refMethod']}, $item, $field);
                }else{
                    $formatValue = $object->{$method[1]}($item->{$field['name']}, $item, $field);
                }
                unset($item->{$field['name']});
                $item->{$field['name']} = $formatValue;

            }
        }

        return $this->success($list);

    }

    /**
     * @param  $query
     * @param  Request $request
     * @param  $indexConfig
     * @throws  BackendException
     */
    public function withRefData(Builder $query, Request $request, $indexConfig)
    {
        $fields = Arr::get($indexConfig, 'fields', []);

        if (!is_array($fields)) {
            throw new BackendException('list.yaml 中fields， 必须为数组格式');
        }

        $columnsConfig = $this->getConfig('Test.Column');
        $columns = Arr::get($columnsConfig, 'fields');

        $indexList = $this->withColumnData($fields, $columns);

        foreach ($indexList as $column) {
            if (Arr::get($column, 'type') != 'ref') {
                continue;
            }
            $query->with(Arr::get($column, 'refMethod') . ":" . Arr::get($column, 'refField', '*'));
        }
    }

    /**
     * @param  Builder $query
     * @param  Request $request
     * @param  $indexConfig
     * @throws  BackendException
     */
    public function search(Builder $query, Request $request, $indexConfig)
    {
        $fields = Arr::get($indexConfig, 'fields', []);

        if (!is_array($fields)) {
            throw new BackendException('list.yaml 中fields， 必须为数组格式');
        }

        $columnsConfig = $this->getConfig('Test.Column');
        $columns = Arr::get($columnsConfig, 'fields');

        $searchList = array_filter($fields, function ($item) {
            return Arr::get($item, 'search', false);
        });

        $searchList = $this->withColumnData($searchList, $columns);

        foreach ($searchList as $column) {

            $searchKey = $this->getInput($request, $column['name']);
            if ($searchKey === false || is_null($searchKey)) {
                continue;
            }

            switch ($column['type']) {
                case "int":
                case "enum":
                    $query->where($column['name'], $searchKey);
                    break;
                case "text":
                    $query->where($column['name'], 'like', "$searchKey%");
                    break;
                case "datetime":
                case "date":
                case "time":
                    if ($searchKey && !is_array($searchKey)) {
                        throw new BackendException("datetime, date, time 类型应为二维数组");
                    }

                    if (count($searchKey) != 2) {
                        throw new BackendException("datetime, date, time 类型应为二维数组");
                    }
                    $query->whereBetween($column['name'], $searchKey[0], $searchKey[1]);
                    break;
                case "enums":
                    if ($searchKey && !is_array($searchKey)) {
                        throw new BackendException("枚举类型应为二维数组");
                    }
                    $query->where($column['name'], $searchKey);
            }
        }

    }

    /**
     * 根据配置， 和请求参数进行排序
     * @param  Builder $query
     * @param  Request $request
     * @param  $indexConfig
     * @throws  BackendException
     */
    public function order(Builder $query, Request $request, $indexConfig)
    {
        $requestSort = $this->getInput($request, '_order_by');
        //读取配置文件
        $defaultOrderBy = Arr::get($indexConfig, 'orderBy');
        $orderByData = array_map(function ($item) {
            $item = trim($item);;
            $item = explode(" ", $item);
            return array_map(function ($item2) {
                return trim($item2, '`');
            }, $item);
        }, explode(",", $defaultOrderBy));


        if ($requestSort === false && Arr::get($indexConfig, 'orderBy')) {
            foreach ($orderByData as $orderBy) {
                if (count($orderByData) != 2) {
                    throw new BackendException("不合法的排序语句: " . json_encode($orderBy, 256));
                }
                $query->orderBy($orderBy[0], $orderBy[1]);
            }

        } else if (is_array($requestSort)) {
            foreach ($orderByData as $orderBy) {
                if (count($orderByData) != 2) {
                    throw new BackendException("不合法的排序语句: " . json_encode($orderBy, 256));
                }

                $orderInput = $this->getInput($request, $orderBy[0]);
                if ($orderInput === false) {
                    continue;
                }

                if (count($orderInput) != 2) {
                    throw new BackendException("不合法的排序参数: " . json_encode($orderInput, 256));
                }

                $query->orderBy($orderInput[0], $orderInput[1]);
            }
        }
    }


    /**
     * 创建页面
     * @param  TestCreateRequest $request
     */
    public function create(TestCreateRequest $request)
    {

    }

    /**
     * 保存
     * @param  TestStoreRequest $request
     * @return  \App\Http\Resources\ErrorResource|\App\Http\Resources\SuccessResource
     */
    public function store(TestStoreRequest $request)
    {
        //批量赋值
        $createConfig = $this->getConfig('Test.PageConfig.Create');
        $columnConfig = $this->getConfig("Test.Column");
        $fields = Arr::get($createConfig, 'fields');
        $columns = Arr::get($columnConfig, 'fields');

        $fieldsWithColumns = $this->withColumnData($fields, $columns);
        $newModel = new TestModel();
        try {
            DB::beginTransaction();
            $this->fillData($fieldsWithColumns, $newModel, $request);
            $newModel->save();
            DB::commit();
            return $this->success($newModel);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failed($e->getMessage());
        }
    }

    public static function mergerConfig($columnConfig, $config)
    {

        dd($columnConfig);
        foreach ($config['fields'] as $key => $field) {
            $data = array_filter($columnConfig['fields'], function ($item) use ($field) {
                return Arr::get($item, 'name') == Arr::get($field, 'name');
            });
            if ($data) {
                $data = array_values($data);
                $config['fields'][$key] = array_merge($data[0], $field);
            }
        }
        return $config;
    }

    /**
     * 显示页面
     * @param  TestShowRequest $request
     * @param  $id
     * @return  \App\Http\Resources\ErrorResource|\App\Http\Resources\SuccessResource
     * @throws  BackendException
     * @throws  BackendPermissionException
     */
    public function show(TestShowrequest $request, $id)
    {
        $query = TestModel::query();
        $editConfig = $this->getConfig('Test.PageConfig.Edit');
        $columnConfig = $this->getConfig('Test.Column');
        $config = self::mergerConfig($columnConfig, $editConfig);

        $this->withRefData($query, $request, $config);
        $data = $query->where('id', $id)->first();

        foreach ($config['fields'] as $item) {
            if (!isset($item['method'])) {
                continue;
            }
            if (strpos($item['method'], '@') === false) {
                continue;
            }

            $method = explode('@', $item['method']);
            $object = app(__NAMESPACE__ . '\\' . $method[0]);
            if ($item['refMethod']) {
                $formatValue = $object->{$method[1]}($data->{$item['refMethod']}, $data, $item);
                unset($data->{$item['name']});
                $data->{$item['name']} = $formatValue;
            } else {
                $object->{$method[1]}($data->item['name'], $data, $item);
            }
        }

        if (!$data) {
            return $this->failed("未找到分类id：" . $id);
        }

        if (!$this->checkResourcePermission($this->getNowAdmin(), $data)) {
            throw new BackendPermissionException("抱歉您没有权限");
        }

        return $this->success($data);
    }

    /**
     * 编辑页面
     * @param  BackendRequest $request
     */
    public function edit(BackendRequest $request)
    {

    }

    /**
     * 更新
     * @param  TestUpdateRequest $request
     * @return  \App\Http\Resources\ErrorResource|\App\Http\Resources\SuccessResource
     */
    public function update(TestUpdateRequest $request, $id)
    {
        $model = TestModel::find($id);
        if (!$model) {
            return $this->failed("模型未找到, id: " . $id);
        }

        $editConfig = $this->getConfig("Test.PageConfig.Edit");
        $columnConfig = $this->getConfig('Test.Column');

        $fields = Arr::get($editConfig, 'fields');
        $columns = Arr::get($columnConfig, 'fields');

        $columnEditConfig = $this->withColumnData($fields, $columns);

        try {
            DB::beginTransaction();
            $this->fillData($columnEditConfig, $model, $request);
            $model->save();
            DB::commit();
            return $this->success();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failed("保存失败：" . $e->getMessage());
        }
    }


    /**
     * @param  $id
     * @param  TestDestroyRequest $request
     * @return  \App\Http\Resources\ErrorResource|\App\Http\Resources\SuccessResource
     */
    public function destroy($id, TestDestroyRequest $request)
    {
        $model = TestModel::find($id);
        if (!$model) {
            return $this->failed("模型未找到, id: " . $id);
        }

        if (!$this->checkResourcePermission($this->getNowAdmin(), $model)) {
            return $this->failed("您没有权限");
        }

        if ($this->beForeDestroy($model)) {
            $model->delete();
        } else {
            return $this->failed($this->errorMessage || "删除失败");
        }

        return $this->success();
    }

    /**
     * @param  Model $model
     * @return  bool
     */
    public function beForeDestroy(Model $model)
    {
        return true;
    }

    /**
     * @param  $fieldsWithColumns
     * @param  TestModel $model
     * @param  Request $request
     * @return  TestModel
     * @throws  \Exception
     */
    public function fillData($fieldsWithColumns, TestModel $model, Request $request)
    {
        foreach ($fieldsWithColumns as $field) {
            $name = Arr::get($field, 'name');
            if (!$name) {
                throw new BackendException("Test.PageConfig.Create 配置错误，含有空字段名: " . json_encode($fieldsWithColumns, 256));
            }

            if (method_exists($model, 'set' . ucfirst($name))) {
                $methodName = 'set' . ucfirst($name);
                $model->$methodName($request->input($name));
                continue;
            }

            if (Arr::get($field, 'type') != 'ref') {
                //不是关联数据
                $model->$name = $request->input($name);
            } else {
                switch (Arr::get($field, 'refType')) {
                    case "belongsTo"://多对一关联
                    case "enums": //枚举类型
                        $model->$name = $request->input($name);
                        break;
                    case "belongsToMany": //多对多
                        $refMethod = Arr::get($field, 'refMethod');
                        if ($refMethod) {
                            $model->save();
                            $model->$refMethod()->sync($request->input($name));
                        }
                }
            }
        }

        $this->setEntry($model);

        return $model;
    }


    /**
     * @return  mixed
     */
    public function getEntry(): Model
    {
        return $this->entry;
    }

    /**
     * @param  mixed $entry
     */
    public function setEntry(Model $entry): void
    {
        $this->entry = $entry;
    }
}