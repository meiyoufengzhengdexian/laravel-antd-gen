<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-9
 * Time: 下午9:30
 */

namespace App\Http\Backend\Lib;


use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class Tool
{
    public static function getNowAdmin() :Authenticatable
    {
        $admin = Auth::user();
        return $admin;
    }
}