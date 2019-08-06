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

        $cateQuery = TeacherModel::query();
        if ($keyWord !== false) {
            $cateQuery->where('name', 'like', "{$keyWord}%");
        }

        $list = $cateQuery->select(['id', 'name' => 'name'])->limit(20)->get();
        return $this->success($list);
    }
}
