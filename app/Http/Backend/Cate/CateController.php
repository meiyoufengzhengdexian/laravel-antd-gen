<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-10
 * Time: 下午9:50
 */

namespace App\Http\Backend\Cate;


use App\Http\Backend\BackendController;
use App\Http\Backend\Cate\Request\CateIndexRequest;

class CateController extends BackendController
{
    use CateCurd;
}