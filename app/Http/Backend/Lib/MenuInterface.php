<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-6
 * Time: 上午9:39
 */

namespace App\Http\Backend\Lib;


interface MenuInterface
{
    public function getName();
    public function getIcon();
    public function getUri();
}