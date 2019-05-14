<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-6
 * Time: 上午9:37
 */

namespace App\Http\Backend;

use App\Http\Backend\Lib\MenuInterface;
use Illuminate\Support\Collection;

/**
 * 后台菜单控制
 * Class BackendMenu
 * @package App\Http\Backend
 */
class BackendMenu
{
    public static $menuList;

    public function __construct()
    {
        if(!static::$menuList){
            static::setMenuList(collect());
            static::initMenu();
        }
    }

    public static function initMenu()
    {
        
    }

    public static function addMenu(MenuInterface $menu)
    {
        $menuList = static::getMenuList();
        $menuList->push($menu);
    }

    /**
     * @param mixed $menuList
     */
    public static function setMenuList($menuList): void
    {
        self::$menuList = $menuList;
    }

    public static function getMenuList(): Collection
    {
        return static::$menuList;
    }
}