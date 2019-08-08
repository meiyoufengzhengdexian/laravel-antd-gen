<?php
namespace App\Http\Controllers\Backend\Users\Request;



use App\Http\Controllers\Backend\BackendRequest;

class UsersShowRequest extends BackendRequest
{
    public function authorize()
    {
        if($this->checkAuth('backend.users.all', '')){
            return true;
        }
        $action = $this->getRouteAs();
        return $this->checkAuth($action, "您没有查看权限: ". $action);
    }
}
