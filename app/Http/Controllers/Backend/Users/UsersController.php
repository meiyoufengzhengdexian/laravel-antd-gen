<?php

namespace App\Http\Controllers\Backend\Users;

use App\Http\Controllers\Backend\BackendController;
use App\Http\Controllers\Backend\Tag\TagModel;
use App\User;
use Illuminate\Http\Request;

class UsersController extends BackendController
{
    use UsersCurd;

    public function UsersOptions(Request $request)
    {
        $keyWord = $request->input('searchKey', false);

        $cateQuery = UsersModel::query();
        if ($keyWord !== false) {
            $cateQuery->where('name', 'like', "{$keyWord}%");
        }

        $list = $cateQuery->select(['id', 'name' => 'name'])->limit(20)->get();
        return $this->success($list);
    }


    /**
     * 管理员信息
     * @param Request $request
     * @param $id
     * @return \App\Http\Resources\ErrorResource|\App\Http\Resources\SuccessResource
     */
    public function info(Request $request, $id='')
    {
        if(!$id){
            $user = auth()->user();
        }else{
            $user = User::query()->where('id', $id)->first();
        }

        if (!$user) {
            return $this->failed('用户未找到');
        }

        $rules = $user->roles->pluck('name');
        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'introduction' => 'super administrator',
            'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
            'roles' => $rules
        ]);
    }
}
