<?php
namespace App\Http\Controllers\Backend\Course\Request;



use App\Http\Controllers\Backend\BackendRequest;

class CourseShowRequest extends BackendRequest
{
    public function authorize()
    {
        if($this->checkAuth('backend.course.all', '')){
            return true;
        }
        $action = $this->getRouteAs();
        return $this->checkAuth($action, "您没有查看权限: ". $action);
    }
}
