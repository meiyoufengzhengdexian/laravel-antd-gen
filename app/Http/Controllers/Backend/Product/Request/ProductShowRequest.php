<?php
namespace App\Http\Controllers\Backend\Product\Request;



use App\Http\Controllers\Backend\BackendRequest;

class ProductShowRequest extends BackendRequest
{
    public function authorize()
    {
        if($this->checkAuth('backend.product.all', '')){
            return true;
        }
        $action = $this->getRouteAs();
        return $this->checkAuth($action, "您没有查看权限: ". $action);
    }
}
