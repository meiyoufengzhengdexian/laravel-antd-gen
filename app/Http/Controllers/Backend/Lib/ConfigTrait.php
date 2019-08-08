<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-14
 * Time: 下午7:32
 */

namespace App\Http\Controllers\Backend\Lib;


use Spyc;

Trait ConfigTrait
{
    /**
     * 获取yaml配置文件内容
     * @param $filename
     * @return array
     */
    public function getConfig($filename)
    {
        if(defined('ARTISAN_BINARY') && ARTISAN_BINARY == 'artisan'){
            $baseDir = "./app/Http/Controllers/Backend";
        } else {
            $baseDir = "../app/Http/Controllers/Backend";
        }
        $filename = str_replace('.', DIRECTORY_SEPARATOR, $filename);
        $readFileName = "$baseDir/$filename.yaml";

        if (!file_exists($readFileName)) {
            return [];
        }

        $content = file_get_contents($readFileName);
        return Spyc::YAMLLoadString($content);
    }

    /**
     * @param $list
     * @param $columns
     * @return array
     */
    public function withColumnData($list, $columns): array
    {
        $merge = Tool::merge($columns, $list);
        return array_intersect_key($merge, $list);
    }
}
