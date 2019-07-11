<?php


namespace App\Service\Gen;


use App\Service\Gen\Exception\ConfigNotFoundException;
use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class GenPageConfig
{
    /**
     * @param $table
     * @throws ConfigNotFoundException
     */
    public static function index($table, Command $ctx)
    {
        $tableConfig = GenTool::getTableConfigFile($table);
        if(!file_exists($tableConfig)){
            throw new ConfigNotFoundException($tableConfig);
        }

        $tableConfig = Yaml::parseFile($tableConfig);

        $fields = $tableConfig['fields'];

        $indexConfig = [];

        foreach($fields as $field){
            $fieldInfo = [];

            $display = $ctx->ask('是否显示? y/n');
            $display = !$display? 'y' : 'n';

            if(!$display == 'n'){
                continue;
            }

            $search = $ctx->ask('是否开启搜索? y/n');
            $search = !$search ? 'y' : $display;

            $fieldInfo['search'] = !!$search;
            $fieldInfo['name'] = $field['name'];

            $indexConfig[] = $fieldInfo;
        }

        file_put_contents(GenTool::getPageConfigFile($table, 'Index'), Yaml::dump($indexConfig, 50, 2));
    }

    /**
     * @param $table
     * @param Command $ctx
     * @throws ConfigNotFoundException
     */
    public static function create($table, Command $ctx)
    {
        $tableConfig = GenTool::getTableConfigFile($table);
        if(!file_exists($tableConfig)){
            throw new ConfigNotFoundException($tableConfig);
        }

        $tableConfig = Yaml::parseFile(GenTool::getTableConfigFile($table));

        $fields = $tableConfig['fileds'];

        foreach($fields as $field){
            $filedInfo = [];
            $display = $ctx->ask($field['name'].' 要显示在创建表单中吗? y/n');

            $display = !$display? 'y' : $display;

            if($display == 'n'){
                continue;
            }

            $filedInfo['name'] = $field['name'];
            $filedInfo['type'] = $field['type'];

            switch ($filedInfo['type']) {
                case "":
            }

        }

    }
}