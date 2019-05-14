<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-9
 * Time: 下午9:29
 */

namespace App\Http\Backend\Users\Auth;


use App\Http\Backend\BackendAuthInterface;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use PhpParser\Node\Scalar\String_;

class UserGroupAuth implements BackendAuthInterface
{
    public function check(Authenticatable $admin, Model $model, Request $request): bool
    {

    }


    public function getRange(Builder $builder) :Builder
    {

    }
}