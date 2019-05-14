<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-10
 * Time: 下午9:50
 */

namespace App\Http\Backend\Cate\Request;


use App\Http\Backend\BackendRequest;

class CateCreateRequest extends BackendRequest
{
    /**
     * @return bool
     * @throws \App\Http\Backend\BackendException
     */
    public function authorize()
    {
        $action = $this->getRouteAs();
        return $this->checkAuth($action, "您没有查看列表权限: ". $action);
    }
}