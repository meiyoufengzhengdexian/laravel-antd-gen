<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-10
 * Time: 下午9:51
 */

namespace App\Http\Backend\Cate;


use App\Http\Abilities;
use App\Http\AbilitiesResource;
use App\Http\AbilitiesResourceRule;
use App\Http\Backend\BackendException;
use App\Http\Backend\BackendRequest;
use App\Http\Backend\BackendResource;
use App\Http\Backend\Cate\Request\CateCreateRequest;
use App\Http\Backend\Cate\Request\CateIndexRequest;
use App\Http\Backend\Cate\Request\CateStoreRequest;
use App\Http\Backend\Cate\Request\CateUpdateRequest;
use App\Http\Backend\Lib\ConfigTrait;
use App\Http\Backend\Lib\Tool;
use App\Http\Backend\Lib\ToolTrait;
use App\Http\Backend\Tag\TagModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spyc;

trait CateCurd
{
    protected $entry;

    use BackendResource;
    use ConfigTrait;
    use ToolTrait;

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

        $admin = Tool::getNowAdmin();
        if (!$admin->can('backend.cate.all')) {
            //获取资源控制范围
            $resourcePermissionConfig = $this->getConfig('Cate.AuthConfig.ResourcePermission');
            $rps = Arr::get($resourcePermissionConfig, 'resourcePermission');
            $resourceName = array_column($rps, 'resource');

            //读取数据库中对应的资源权限
            $abilitiesResources = AbilitiesResource::query()
                ->whereIn('resource', $resourceName)
                ->get();

            $abilitiesResourcesIds = $abilitiesResources->pluck('id');

            //读取所有权限规则
            $abilities = Abilities::query()->where('entity_type', 'App\Http\AbilitiesResource')
                ->whereIn('entity_id', $abilitiesResourcesIds)
                ->get();

            foreach ($abilities as $ability) {
                if (!$admin->can($ability->name, $abilitiesResources->where('name', $ability->name)->first())) {
                    continue;
                }

                //用户有此权限, 对query进行限定
                $rules = AbilitiesResourceRule::query()->where('resource_id', $ability->entity_id)->get();
                foreach ($rules as $rule) {
                    $rule->condition_param = json_decode($rule->condition_param, true);

                    if ($rule->ref && $rule->ref_type) {
                        switch ($rule->ref_type) {
                            case "belongsTo":
                                $modelClass = $rule->model;
                                $refQuery = $modelClass::query();
                                $this->makeQuery($rule, $refQuery);
                                $ids = $refQuery->pluck($rule->foreign_key);
                                $rule->condition_type = "in";
                                $rule->condition_param = $ids;
                                break;
                        }
                    }

                    $this->makeQuery($rule, $query, 'or');

                }
            }
        }

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

        DB::beginTransaction();
        try {
            foreach ($fieldsWithColumns as $field) {
                $name = Arr::get($field, 'name');
                if (!$name) {
                    throw new BackendException("Cate.PageConfig.Create 配置错误，含有空字段名: " . json_encode($createConfig, 256));
                }

                if (!Arr::get($field, 'refType')) {
                    //不是关联数据
                    $newModel->$name = $request->input($name);
                } else {
                    switch (Arr::get($field, 'refType')) {
                        case "belongsTo"://多对一关联
                        case "enums": //枚举类型
                            $newModel->$name = $request->input($name);
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

                            $newModel->save();
                            $newModel->belongsToMany($refModel, $table, $foreignPivotKey, $relatedPivotKey)->sync($request->input($name));
                    }
                }
            }

            $this->setEntry($newModel);
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
     * @param BackendRequest $request
     */
    public function show(BackendRequest $request)
    {

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
     */
    public function update(CateUpdateRequest $request)
    {

    }

    public function destroy()
    {

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