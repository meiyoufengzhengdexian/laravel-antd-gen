<?php
namespace App\Http\Controllers\Backend\Test;

use App\Http\Controllers\Backend\BackendController;
use App\Http\Controllers\Backend\Tag\TagModel;
use Illuminate\Http\Request;

class TestController extends BackendController
{
    use TestCurd;

    public function TestOptions(Request $request)
    {
        $keyWord = $request->input('searchKey', false);

        $cateQuery = TestModel::query();
        if($keyWord !== false){
            $cateQuery->where('name', 'like', "{$keyWord}%");
        }

        $list = $cateQuery->select(['id', 'name'=>'name'])->limit(20)->get();
        return $this->success($list);
    }
}