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
        $id = $request->input('id', false);

        $query = CourseModel::query();
        if($keyWord !== false){
            $query->where('name', 'like', "{$keyWord}%");
        }
        $id !== false && $query->where('id', $id);


        $list = $query->select(['id', 'name'=>'name'])->limit(20)->get();
        return $this->success($list);
    }
}
