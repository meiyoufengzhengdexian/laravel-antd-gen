<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-10
 * Time: 下午10:07
 */

namespace App\Http\Backend\Cate\Request;


use App\Http\Backend\BackendException;
use App\Http\Backend\BackendRequest;
use App\Http\Backend\Cate\CateController;
use Illuminate\Http\Request;

class CateIndexRequest extends BackendRequest
{
    /**
     * @return bool
     * @throws BackendException
     */
    public function authorize()
    {
        $action = $this->getRouteAs();
        return $this->checkAuth($action, "您没有创建权限: ". $action);
    }
}