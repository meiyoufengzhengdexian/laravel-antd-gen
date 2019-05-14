<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-14
 * Time: 下午6:56
 */

namespace App\Http\Backend\Cate\Request;


use App\AbilitiesResourceRule;
use App\Http\Abilities;
use App\Http\AbilitiesResource;
use App\Http\Backend\BackendException;
use App\Http\Backend\BackendRequest;
use App\Http\Backend\Lib\AuthResourceTrait;
use App\Http\Backend\Lib\ConfigTrait;
use App\Http\Backend\Lib\ToolTrait;

class CateStoreRequest extends BackendRequest
{
    use ConfigTrait;
    use ToolTrait;
    use AuthResourceTrait;

    /**
     * @return bool
     * @throws \App\Http\Backend\BackendException
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
        $createConfig = $this->getConfig('Cate.PageConfig.Create');


        return [

        ];
    }
}