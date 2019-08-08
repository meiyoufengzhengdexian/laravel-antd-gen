<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Backend\BackendController;
use App\Http\Controllers\Backend\Tag\TagModel;
use Illuminate\Http\Request;

class TeacherController extends BackendController
{
    use TeacherCurd;

    public function TeacherOptions(Request $request)
    {
        $keyWord = $request->input('searchKey', false);
        $id = $request->input('id', false);

        $teacherQuery = TeacherModel::query();
        if ($keyWord !== false) {
            $teacherQuery->where('name', 'like', "{$keyWord}%");
        }
        $id !== false && $teacherQuery->where('id', $id);

        $list = $teacherQuery->select(['id', 'name' => 'name'])->limit(20)->get();
        return $this->success($list);
    }
}
