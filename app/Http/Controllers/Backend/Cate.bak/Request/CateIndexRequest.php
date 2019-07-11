<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-10
 * Time: 下午10:07
 */

namespace App\Http\Controllers\Backend\Cate\Request;

use App\Http\Controllers\Backend\BackendRequest;
use Illuminate\Http\Request;

class CateIndexRequest extends BackendRequest
{
    /**
     * @return bool
     * @throws \App\Http\Controllers\Backend\BackendException
     */
    public function authorize()
    {
        $action = $this->getRouteAs();
        return $this->checkAuth($action, "您没有创建权限: ". $action);
    }
}