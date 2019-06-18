<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-10
 * Time: 下午9:50
 */

namespace App\Http\Controllers\Backend\Cate;

use App\Http\Controllers\Backend\BackendController;
use App\Http\Controllers\Backend\Tag\TagModel;
use Illuminate\Http\Request;

class CateController extends BackendController
{
    use CateCurd;

    public function cateOptions(Request $request)
    {
        $keyWord = $request->input('searchKey', false);

        $cateQuery = CateModel::query();
        if($keyWord !== false){
            $cateQuery->where('name', 'like', "{$keyWord}%");
        }

        $list = $cateQuery->select(['id', 'name'=>'name'])->limit(20)->get();
        return $this->success($list);
    }

    public function tags()
    {
        return $this->success(TagModel::all());
    }
}