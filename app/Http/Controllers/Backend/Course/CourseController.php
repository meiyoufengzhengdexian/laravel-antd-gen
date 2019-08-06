<?php
namespace App\Http\Controllers\Backend\Course;

use App\Http\Controllers\Backend\BackendController;
use App\Http\Controllers\Backend\Tag\TagModel;
use Illuminate\Http\Request;

class CourseController extends BackendController
{
    use CourseCurd;

    public function CourseOptions(Request $request)
    {
        $keyWord = $request->input('searchKey', false);

        $cateQuery = CourseModel::query();
        if($keyWord !== false){
            $cateQuery->where('name', 'like', "{$keyWord}%");
        }

        $list = $cateQuery->select(['id', 'name'=>'name'])->limit(20)->get();
        return $this->success($list);
    }
}