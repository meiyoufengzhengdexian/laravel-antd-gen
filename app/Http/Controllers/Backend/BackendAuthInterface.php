<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-9
 * Time: 下午9:29
 */

namespace App\Http\Controllers\Backend;


use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

interface BackendAuthInterface
{
    /**
     * @param Authenticatable $admin
     * @param Model $model
     * @param Request $request
     * @return bool
     * 对此数据是否有操作权限
     */
    public function check(Authenticatable $admin, Model $model, Request $request) : bool;

    /**
     * @param Builder $builder
     * @return mixed
     * 获取列表范围
     */
    public function getRange(Builder $builder);

}