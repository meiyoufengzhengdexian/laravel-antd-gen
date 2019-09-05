<?php
namespace App\Http\Controllers\Backend\Product\Request;


use App\Http\Abilities;


use App\Http\Controllers\Backend\BackendRequest;
use App\Http\Controllers\Backend\Lib\AuthResourceTrait;
use App\Http\Controllers\Backend\Lib\ConfigTrait;
use App\Http\Controllers\Backend\Lib\ToolTrait;
use Illuminate\Support\Arr;

class ProductStoreRequest extends BackendRequest
{
    use ConfigTrait;
    use ToolTrait;
    use AuthResourceTrait;


    /**
     * @return  bool
     * @throws  \App\Http\Controllers\Backend\BackendException
     */
    public function authorize()
    {
        if($this->checkAuth('backend.product.all', '')){
            return true;
        }
        $action = $this->getRouteAs();
        return $this->checkAuth($action, "您没有新建权限: ". $action);
    }

    public function rules()
    {
        $columnConfig = $this->getConfig("Product.Column");
        $columns = Arr::get($columnConfig, 'fields');
        $returnRule = $this->makeRuleAndMessage($columns);
        return $returnRule;
    }


}
