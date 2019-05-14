<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-6
 * Time: 上午9:39
 */

namespace App\Http\Backend\Lib;


class Menu implements MenuInterface
{
    public function getName()
    {
        return "查看列表";
    }

    public function getIcon()
    {
        return "";
    }

    public function getUri()
    {
        return "users/list";
    }

}