<?php
namespace App\Http\Controllers\Backend\Test\Request;

use App\Http\Controllers\Backend\BackendRequest;
use Illuminate\Http\Request;

class TestIndexRequest extends BackendRequest
{
    /**
     * @return  bool
     * @throws  \App\Http\Controllers\Backend\BackendException
     */
    public function authorize()
    {
        $action = $this->getRouteAs();
        return $this->checkAuth($action, "您没有创建权限: ". $action);
    }
}