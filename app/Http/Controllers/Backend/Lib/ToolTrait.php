<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-14
 * Time: 下午7:33
 */

namespace App\Http\Controllers\Backend\Lib;


use App\Http\Controllers\Backend\BackendException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait ToolTrait
{
    /**
     * 获取当前请求
     * @return Request
     */
    public function getRequest(): Request
    {
        return app(Request::class);
    }


    public function getNowAdmin() :Authenticatable
    {
        $admin = Auth::user();
        return $admin;
    }


    /**
     * @param Request $request
     * @param $name
     * @return array|string|null
     * @throws BackendException
     */
    public function getInput(Request $request, $name)
    {
        if (!isset($name)) {
            throw new BackendException('搜索配置中应包含字段名称: ' . json_encode($name, 256));
        }
        $searchKey = $request->input($name, false);
        return $searchKey;
    }


    /**
     * @param $rule
     * @param Builder $query
     * @param string $type
     * @throws BackendException
     */
    public function makeQuery($rule, Builder $query, $type = "and")
    {
        switch ($rule->condition_type) {
            case "=":
            case "<":
            case ">":
            case "<>":
            case "!=":
                if ($type == "and") {
                    $query->where($rule->field_name, $rule->condition_type, array_get($rule->condition_param, 0));
                } else {
                    $query->orWhere($rule->field_name, $rule->condition_type, array_get($rule->condition_param, 0));
                }
                break;
            case "like":
                $param = array_get($rule->condition_param, 0);
                if ($param) {
                    $param .= "%";
                    if ($type == "and") {
                        $query->where($rule->field_name, 'like', $param);
                    } else {
                        $query->orWhere($rule->field_name, 'like', $param);
                    }
                } else {
                    if ($type == "and") {
                        $query->where('id', '<', -9999999);
                    } else {
                        $query->orWhere('id', '<', -9999999);
                    }
                }
                break;
            case "between":
                if (count($rule->condition_type) != 2) {
                    throw new BackendException("资源权限规则参数错误 Between 参数数组长度应该等与2 ruleID： #{$rule->id}");
                }
                if ($type == "and") {
                    $query->whereBetween($rule->field_name, array_values($rule->condition_param));
                } else {
                    $query->orWhereBetween($rule->field_name, array_values($rule->condition_param));
                }
                break;
            case "in":
                if ($type == "and") {
                    $query->whereIn($rule->field_name, array_values($rule->condition_param));
                } else {
                    $query->orWhereIn($rule->field_name, array_values($rule->condition_param));
                }
        }
    }
}