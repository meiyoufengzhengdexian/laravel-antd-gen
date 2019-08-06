<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-14
 * Time: 下午7:32
 */

namespace App\Http\Controllers\Backend\Lib;


use Illuminate\Support\Arr;
use Spyc;

trait ConfigTrait
{
    /**
     * 获取yaml配置文件内容
     * @param $filename
     * @return array
     */
    public function getConfig($filename)
    {
        $baseDir = "../app/Http/Controllers/Backend";
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
        return array_merge_recursive($columns, $list);
    }
}
