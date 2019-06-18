<?php

namespace App\Http\Controllers\Backend\City;

use App\Http\Controllers\Backend\BackendResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
    use BackendResource;

    public function index(Request $request)
    {
        $keyWord = $request->input('searchKey');

        $cityQuery = CityModel::query();
        if ($keyWord) {
            $cityQuery->where('name', 'like', $keyWord . '%');
        }

        $list = $cityQuery->get();
        return $this->success($list);

    }

    public function options(Request $request)
    {
        $keyWord = $request->input('searchKey');

        $cityQuery = CityModel::query();
        if ($keyWord) {
            $cityQuery->where('name', 'like', $keyWord . '%');
        }

        $list = $cityQuery->limit(20)->select(['id', 'name'=>'name'])->get();
        return $this->success($list);
    }
}
