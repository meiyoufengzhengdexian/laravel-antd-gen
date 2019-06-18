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
        //获取数据类型
        foreach ($list as $key => $item) {
            $filter = array_filter($columns, function ($column) use ($item) {
                return Arr::get($column, 'name', '##collumn_name')
                    == Arr::get($item, 'name', "##index_name");
            });

            if ($filter) {
                $filter = array_values($filter);
            }

            if (count($filter) < 1) {
                $list[$key]['type'] = "text";
            } else {
                $list[$key] = array_merge($item, $filter[0]);
            }
        }


        return $list;

    }
}