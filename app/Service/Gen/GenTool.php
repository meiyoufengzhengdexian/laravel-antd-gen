<?php


namespace App\Service\Gen;


use Illuminate\Support\Str;

class GenTool
{
    public static function getDir($tableName)
    {
        return ucfirst(Str::camel($tableName));
    }

    public static function getTableConfigFile($tableName)
    {
        $tableConfig = './app/Http/Controllers/Backend/' . GenTool::getDir($tableName).'/Column.yaml';
        return $tableConfig;
    }

    public static function getModelFile($tableName)
    {
        $file = './app/Http/Controllers/Backend/' . GenTool::getDir($tableName).'/'.GenTool::getDir($tableName).'Model.php';
        return $file;
    }

    public static function getPermissionFile($tableName)
    {
        $file = './app/Http/Controllers/Backend/' . GenTool::getDir($tableName).'/AuthConfig/Permission.yaml';
        return $file;
    }

    public static function getRequestFile($tableName, $prefix)
    {
        $file = './app/Http/Controllers/Backend/' . GenTool::getDir($tableName).'/Request/'.GenTool::getDir($tableName).$prefix.'Request.php';
        return $file;
    }

    public static function getPageConfigFile($tableName, $prefix)
    {
        $file = './app/Http/Controllers/Backend/' . GenTool::getDir($tableName).'/PageConfig/'.$prefix.'.yaml';
        return $file;
    }

    public static function getCrudFile($tableName)
    {
        $file = './app/Http/Controllers/Backend/' . GenTool::getDir($tableName)."/".GenTool::getDir($tableName).'Curd.php';
        return $file;

    }
    public static function getControllerFile($tableName)
    {
        $file = './app/Http/Controllers/Backend/' . GenTool::getDir($tableName)."/".GenTool::getDir($tableName).'Controller.php';
        return $file;
    }
    public static function getRefTypeList()
    {
        return [
            '1' => 'belongsTo',
            '2' => 'hasMany',
            '3' => 'belongsToMany'
        ];
    }

    public static function selectRefType($id)
    {
        if (isset(static::getRefTypeList()[$id])){
            return static::getRefTypeList()[$id];
        }else{
            return $id;
        }
    }



}