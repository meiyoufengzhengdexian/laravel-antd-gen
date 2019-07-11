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
        echo "---------------Index-----------------------\n";
        $tableConfig = GenTool::getTableConfigFile($table);
        if (!file_exists($tableConfig)) {
            throw new ConfigNotFoundException($tableConfig);
        }

        $tableConfig = Yaml::parseFile($tableConfig);

        $fields = $tableConfig['fields'];

        $indexConfig = [];
        $indexConfig['orderBy'] = '`created_at` desc, `id` asc';
        $indexConfig['perPage'] = 20;
        $indexConfig['fields'] = [];

        foreach ($fields as $field) {
            $fieldInfo = [];

            $display = $ctx->ask($field['name'].' 是否显示? y/n');
            $display = !$display ? 'y' : 'n';

            if (!$display == 'n') {
                continue;
            }

            $search = $ctx->ask($field['name'].' 是否开启搜索? y/n');

            $search = !$search ? 'y' : $display;

            $fieldInfo['search'] = !!$search;
            $fieldInfo['name'] = $field['name'];

            $indexConfig['fields'][] = $fieldInfo;
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
        echo "---------------Create-----------------------\n";
        $tableConfig = GenTool::getTableConfigFile($table);
        if (!file_exists($tableConfig)) {
            throw new ConfigNotFoundException($tableConfig);
        }

        $tableConfig = Yaml::parseFile(GenTool::getTableConfigFile($table));

        $fields = $tableConfig['fields'];

        $createConfig = [];
        $createConfig['fields'] = [];
        $createConfig['layout'] = 'center';

        foreach ($fields as $field) {
            $filedInfo = [];
            $display = $ctx->ask($field['name'] . ' 要显示在创建表单中吗? y/n');

            $display = !$display ? 'y' : $display;

            if ($display == 'n') {
                continue;
            }

            $filedInfo['name'] = $field['name'];
            $filedInfo['type'] = $field['type'];

            switch ($filedInfo['type']) {
                case "enum":
                    $field['type'] = 'radio';
                    $field['options'] = [
                        ['text' => '开', 'value' => 1],
                        ['text' => '关', 'value' => 0],
                    ];
            }

            $createConfig['fields'][] = $filedInfo;
        }

        file_put_contents(GenTool::getPageConfigFile($table, 'Create'),
            Yaml::dump($createConfig, 50, 2));

    }


    /**
     * @param $table
     * @param Command $ctx
     * @throws ConfigNotFoundException
     */
    public static function edit($table, Command $ctx)
    {
        echo "---------------Edit-----------------------\n";
        $tableConfig = GenTool::getTableConfigFile($table);
        if (!file_exists($tableConfig)) {
            throw new ConfigNotFoundException($tableConfig);
        }

        $tableConfig = Yaml::parseFile(GenTool::getTableConfigFile($table));

        $fields = $tableConfig['fields'];

        $editConfig = [];
        $editConfig['fields'] = [];
        $editConfig['layout'] = 'center';


        foreach ($fields as $field) {
            $filedInfo = [];
            $display = $ctx->ask($field['name'] . ' 要显示在创建表单中吗? y/n');

            $display = !$display ? 'y' : $display;

            if ($display == 'n') {
                continue;
            }

            $filedInfo['name'] = $field['name'];
            $filedInfo['type'] = $field['type'];

            switch ($filedInfo['type']) {
                case "enum":
                    $field['type'] = 'radio';
                    $field['options'] = [
                        ['text' => '开', 'value' => 1],
                        ['text' => '关', 'value' => 0],
                    ];
            }

            $editConfig['fields'][] = $filedInfo;
        }

        file_put_contents(GenTool::getPageConfigFile($table, 'Edit'),
            Yaml::dump($editConfig, 50, 2));
    }



}