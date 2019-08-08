<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-9
 * Time: 下午9:30
 */

namespace App\Http\Controllers\Backend\Lib;


use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class Tool
{
    public static function merge($array1, $array2)
    {
        foreach ($array1 as $array1Key => $array1Value) {
            if (is_array($array1Value)) {
                if (isset($array2[$array1Key])) {
                    $array1[$array1Key] = static::merge($array1Value, $array2[$array1Key]);
                }
            } else {
                $array1[$array1Key] = isset($array2[$array1Key]) ? $array2[$array1Key] : $array1Value;
            }
        }
        foreach ($array2 as $array2Key => $array2Value) {
            if (!isset($array1[$array2Key])) {
                $array1[$array2Key] = $array2Value;
            }
        }
        return $array1;
    }
}
