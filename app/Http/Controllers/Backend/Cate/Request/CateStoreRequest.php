<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-14
 * Time: 下午6:56
 */

namespace App\Http\Controllers\Backend\Cate\Request;


use App\Http\Abilities;


use App\Http\Controllers\Backend\BackendRequest;
use App\Http\Controllers\Backend\Lib\AuthResourceTrait;
use App\Http\Controllers\Backend\Lib\ConfigTrait;
use App\Http\Controllers\Backend\Lib\ToolTrait;
use Illuminate\Support\Arr;
use PhpParser\Node\Expr\Array_;

class CateStoreRequest extends BackendRequest
{
    use ConfigTrait;
    use ToolTrait;
    use AuthResourceTrait;


    /**
     * @return bool
     * @throws \App\Http\Controllers\Backend\BackendException
     */
    public function authorize()
    {
        if ($this->checkAuth('backend.cate.all')) {
            return true;
        }

        $action = $this->getRouteAs();
        if ($this->checkAuth($action, "您没有权限 : " . $action)) {
            return true;
        }

        return false;
    }

    public function rules()
    {
        $columnConfig = $this->getConfig("Cate.Column");
        $columns = Arr::get($columnConfig, 'column');
        $returnRule = $this->makeRuleAndMessage($columns);
        return $returnRule;
    }


}