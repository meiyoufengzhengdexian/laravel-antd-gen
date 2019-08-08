<?php
namespace App\Http\Controllers\Backend\Users\Request;

use App\Http\Controllers\Backend\BackendRequest;
use Illuminate\Http\Request;

class UsersIndexRequest extends BackendRequest
{
    /**
     * @return  bool
     * @throws  \App\Http\Controllers\Backend\BackendException
     */
    public function authorize()
    {
        if($this->checkAuth('backend.users.all', '')){
            return true;
        }
        $action = $this->getRouteAs();
        return $this->checkAuth($action, "您没有查看列表权限: ". $action);
    }
}
