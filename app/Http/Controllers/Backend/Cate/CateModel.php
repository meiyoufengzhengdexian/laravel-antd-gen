<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-10
 * Time: 下午9:50
 */

namespace App\Http\Controllers\Backend\Cate;



use App\Http\Controllers\Backend\BackendModel;
use App\Http\Controllers\Backend\City\CityModel;
use App\Http\Controllers\Backend\Tag\TagModel;

class CateModel extends BackendModel
{
    protected $table = 'cate';

    public function getCity()
    {
        return $this->belongsTo(CityModel::class, 'city', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(TagModel::class, 'cate_tag', 'cate_id', 'tag_id');
    }

    public function getParent()
    {
        return $this->belongsTo(CateModel::class, 'pid', 'id');
    }

    public function formatTags($value)
    {
        if(!$value){
            return [];
        }
        return $value->pluck('tag_id');
    }
}