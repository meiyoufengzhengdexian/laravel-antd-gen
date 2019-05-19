<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-10
 * Time: 下午9:51
 */

namespace App\Http\Controllers\Backend\Cate;


use App\Http\Abilities;

use App\Http\Controllers\Backend\BackendException;
use App\Http\Controllers\Backend\BackendPermissionException;
use App\Http\Controllers\Backend\BackendRequest;
use App\Http\Controllers\Backend\BackendResource;
use App\Http\Controllers\Backend\Cate\Request\CateCreateRequest;
use App\Http\Controllers\Backend\Cate\Request\CateDestroyRequest;
use App\Http\Controllers\Backend\Cate\Request\CateIndexRequest;
use App\Http\Controllers\Backend\Cate\Request\CateShowRequest;
use App\Http\Controllers\Backend\Cate\Request\CateStoreRequest;
use App\Http\Controllers\Backend\Cate\Request\CateUpdateRequest;
use App\Http\Controllers\Backend\Lib\AuthResourceTrait;
use App\Http\Controllers\Backend\Lib\ConfigTrait;
use App\Http\Controllers\Backend\Lib\ToolTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spyc;

trait CateCurd
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

    private function checkResourcePermission($admin, CateModel $cateModel)
    {
        return true;
    }

    /**
     * 列表
     * @param CateIndexRequest $request
     * @return \App\Http\Resources\SuccessResource
     * @throws BackendException
     */
    public function index(CateIndexRequest $request)
    {
        $indexConfig = $this->getConfig('Cate.PageConfig.Index');
        $prePage = $request->input('perPage',
            Arr::get($indexConfig, 'perPage',
                config('backend.defaultPerPage', 20)));

        $query = CateModel::query();
        $this->authRange($this->getNowAdmin(), $query);

        $this->search($query, $request, $indexConfig);
        $this->order($query, $request, $indexConfig);

        $field = array_column(Arr::get($indexConfig, 'list'), 'name');
        $list = $query->select($field)->paginate($prePage);
        return $this->success($list);

    }

    /**
     * @param Builder $query
     * @param Request $request
     * @param $indexConfig
     * @throws BackendException
     */
    public function search(Builder $query, Request $request, $indexConfig)
    {
        $list = Arr::get($indexConfig, 'list', []);

        if (!is_array($list)) {
            throw new BackendException('list.yaml 中list， 必须为数组格式');
        }

        $columnsConfig = $this->getConfig('Cate.Column');
        $columns = Arr::get($columnsConfig, 'column');

        $searchList = array_filter($list, function ($item) {
            return Arr::get($item, 'search', false);
        });

        $searchList = $this->withColumnData($searchList, $columns);

        foreach ($searchList as $column) {

            $searchKey = $this->getInput($request, $column['name']);
            if ($searchKey === false) {
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
     * @param Builder $query
     * @param Request $request
     * @param $indexConfig
     * @throws BackendException
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
     * @param CateCreateRequest $request
     */
    public function create(CateCreateRequest $request)
    {

    }

    /**
     * 保存
     * @param CateStoreRequest $request
     * @return \App\Http\Resources\ErrorResource|\App\Http\Resources\SuccessResource
     */
    public function store(CateStoreRequest $request)
    {
        //批量赋值
        $createConfig = $this->getConfig('Cate.PageConfig.Create');
        $columnConfig = $this->getConfig("Cate.Column");
        $fields = Arr::get($createConfig, 'fields');
        $columns = Arr::get($columnConfig, 'column');

        $fieldsWithColumns = $this->withColumnData($fields, $columns);
        $newModel = new CateModel();
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

    /**
     * 显示页面
     * @param CateShowRequest $request
     * @param $id
     * @return \App\Http\Resources\ErrorResource|\App\Http\Resources\SuccessResource
     * @throws BackendException
     * @throws BackendPermissionException
     */
    public function show(CateShowrequest $request, $id)
    {
        $model = CateModel::find($id);

        if (!$model) {
            return $this->failed("未找到分类id：" . $id);
        }

        if (!$this->checkResourcePermission($this->getNowAdmin(), $model)) {
            throw new BackendPermissionException("抱歉您没有权限");
        }

        return $this->success($model);
    }

    /**
     * 编辑页面
     * @param BackendRequest $request
     */
    public function edit(BackendRequest $request)
    {

    }

    /**
     * 更新
     * @param CateUpdateRequest $request
     * @return \App\Http\Resources\ErrorResource|\App\Http\Resources\SuccessResource
     */
    public function update(CateUpdateRequest $request, $id)
    {
        $model = CateModel::find($id);
        if (!$model) {
            return $this->failed("模型未找到, id: " . $id);
        }
        if (!$this->checkResourcePermission($this->getNowAdmin(), $model)) {
            return $this->failed("您没有权限");
        }

        $editConfig = $this->getConfig("Cate.PageConfig.Edit");
        $columnConfig = $this->getConfig('Cate.Column');

        $fields = Arr::get($editConfig, 'fields');
        $columns = Arr::get($columnConfig, 'column');

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
     * @param $id
     * @param CateDestroyRequest $request
     * @return \App\Http\Resources\ErrorResource|\App\Http\Resources\SuccessResource
     */
    public function destroy($id, CateDestroyRequest $request)
    {
        $model = CateModel::find($id);
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
     * @param Model $model
     * @return bool
     */
    public function beForeDestroy(Model $model)
    {
        return true;
    }

    /**
     * @param $fieldsWithColumns
     * @param CateModel $model
     * @param Request $request
     * @return CateModel
     * @throws \Exception
     */
    public function fillData($fieldsWithColumns, CateModel $model, Request $request)
    {
        foreach ($fieldsWithColumns as $field) {
            $name = Arr::get($field, 'name');
            if (!$name) {
                throw new BackendException("Cate.PageConfig.Create 配置错误，含有空字段名: " . json_encode($fieldsWithColumns, 256));
            }

            if (method_exists($model, 'set' . ucfirst($name))) {
                $methodName = 'set' . ucfirst($name);
                $model->$methodName($request->input($name));
                continue;
            }

            if (!Arr::get($field, 'refType')) {
                //不是关联数据
                $model->$name = $request->input($name);
            } else {
                switch (Arr::get($field, 'refType')) {
                    case "belongsTo"://多对一关联
                    case "enums": //枚举类型
                        $model->$name = $request->input($name);
                        break;
                    case "belongsToMany": //多对多
                        $refModel = Arr::get($field, 'model');
                        if (!$refModel) throw new BackendException('未找到关联模型：　' . json_encode($field, 256));

                        $table = Arr::get($field, 'table');
                        if (!$table) throw new BackendException('未找到belongsToMany表配置：　' . json_encode($field, 256));

                        $foreignPivotKey = Arr::get($field, 'foreignPivotKey');
                        if (!$foreignPivotKey) throw new BackendException('未找到foreignPivotKey的配置：　' . json_encode($field, 256));

                        $relatedPivotKey = Arr::get($field, 'relatedPivotKey');
                        if (!$relatedPivotKey) throw new BackendException('未找到relatedPivotKey的配置：　' . json_encode($field, 256));

                        $model->save();
                        $model->belongsToMany($refModel, $table, $foreignPivotKey, $relatedPivotKey)->sync($request->input($name));
                }
            }
        }

        $this->setEntry($model);

        return $model;
    }


    /**
     * @return mixed
     */
    public function getEntry(): Model
    {
        return $this->entry;
    }

    /**
     * @param mixed $entry
     */
    public function setEntry(Model $entry): void
    {
        $this->entry = $entry;
    }
}