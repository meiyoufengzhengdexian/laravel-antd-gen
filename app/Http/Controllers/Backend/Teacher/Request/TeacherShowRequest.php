<?php
namespace App\Http\Controllers\Backend\Teacher\Request;



use App\Http\Controllers\Backend\BackendRequest;

class TeacherShowRequest extends BackendRequest
{
    public function authorize()
    {
        if($this->checkAuth('backend.teacher.all', '')){
            return true;
        }
        $action = $this->getRouteAs();
        return $this->checkAuth($action, "您没有查看权限: ". $action);
    }
}
