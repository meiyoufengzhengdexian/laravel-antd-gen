<?php
namespace App\Http\Controllers\Backend\Product\Request;


use App\Http\Controllers\Backend\BackendException;
use App\Http\Controllers\Backend\BackendRequest;
use App\Http\Controllers\Backend\Lib\AuthResourceTrait;
use App\Http\Controllers\Backend\Lib\ConfigTrait;
use App\Http\Controllers\Backend\Lib\ToolTrait;


class ProductDestroyRequest extends BackendRequest
{
    use ConfigTrait;
    use ToolTrait;
    use AuthResourceTrait;

    /**
     * @return  bool
     * @throws  BackendException
     * @throws  \App\Http\Controllers\Backend\BackendException
     */
    public function authorize()
    {
        if ($this->checkAuth('backend.product.all')) {
            return true;
        }

        $action = $this->getRouteAs();
        return $this->checkAuth($action, "您没有权限 : " . $action);
    }
}
