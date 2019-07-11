<?php


namespace App\Service\Gen;


use Symfony\Component\Yaml\Yaml;

class GenPermission
{
    public static function gen($tableName)
    {
        $permissionYaml = [
            'permission' => [
                [
                    'name' => "backend.$tableName.all",
                    'desc' => '所有权限'
                ],
                [
                    'name' => "backend.$tableName.index",
                    'desc' => '查看列表权限'
                ],
                [
                    'name' => "backend.$tableName.store",
                    'desc' => '新建保存'
                ],
                [
                    'name' => "backend.$tableName.update",
                    'desc' => '更新'
                ],
                [
                    'name' => "backend.$tableName.destroy",
                    'desc' => '删除'
                ],
                [
                    'name' => "backend.$tableName.all",
                    'desc' => '所有权限'
                ],
            ]
        ];

        file_put_contents(GenTool::getPermissionFile($tableName), Yaml::dump($permissionYaml, 50, 2));
    }
}