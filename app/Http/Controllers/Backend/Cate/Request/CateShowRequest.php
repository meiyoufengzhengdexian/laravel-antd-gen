<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-10
 * Time: 下午10:07
 */

namespace App\Http\Controllers\Backend\Cate\Request;



use App\Http\Controllers\Backend\BackendRequest;

class CateShowRequest extends BackendRequest
{
    public function authorize()
    {
        return true;
    }
}