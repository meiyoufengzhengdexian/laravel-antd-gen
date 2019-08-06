<?php
namespace App\Http\Controllers\Backend\Teacher\Request;

use App\Http\Controllers\Backend\BackendRequest;
use Illuminate\Http\Request;

class TeacherIndexRequest extends BackendRequest
{
    /**
     * @return  bool
     * @throws  \App\Http\Controllers\Backend\BackendException
     */
    public function authorize()
    {
        if($this->checkAuth('backend.teacher.all', '')){
            return true;
        }
        $action = $this->getRouteAs();
        return $this->checkAuth($action, "您没有查看列表权限: ". $action);
    }
}
