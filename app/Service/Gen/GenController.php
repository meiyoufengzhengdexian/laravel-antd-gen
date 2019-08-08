<?php


namespace App\Service\Gen;


use App\Http\Controllers\Backend\Lib\ConfigTrait;
use Illuminate\Support\Arr;
use phpDocumentor\Reflection\Types\Self_;

class GenController
{
    use ConfigTrait;
    public static function crud($table)
    {
        $crudString = "<?php\n";
        $crudString .= view('gen.curd', compact('table')) -> __toString();
        file_put_contents(GenTool::getCrudFile($table), $crudString);
    }


    public static function controller($table)
    {
        $crudString = "<?php\n";
        $crudString .= view('gen.controller', compact('table')) -> __toString();
        file_put_contents(GenTool::getControllerFile($table), $crudString);
    }

    public static function vueIndex($tableName)
    {
        $gen = new self();

        $config = $gen->getConfig(GenTool::getDir($tableName).'.Column');
        $indexConfig = $gen->getConfig(GenTool::getDir($tableName).'.PageConfig.index');
        $editConfig = $gen->getConfig(GenTool::getDir($tableName).'.PageConfig.edit');

        $indexConfig = $gen->withColumnData($indexConfig['fields'], $config['fields']);
        $editConfig = $gen->withColumnData($editConfig['fields'], $config['fields']);

        $baseConfig = $gen->getConfig(GenTool::getDir($tableName).'.'.GenTool::getDir($tableName));

        $string = view('gen.index', compact('indexConfig', 'editConfig', 'config', 'tableName', 'baseConfig'))->__toString();
        file_put_contents(GenTool::getIndexDir($tableName), $string);
    }

    public static function vueApi($tableName)
    {
        $gen = new self();
        $config = $gen->getConfig(GenTool::getDir($tableName).'.Column');
        $indexConfig = $gen->getConfig(GenTool::getDir($tableName).'.PageConfig.index');
        $editConfig = $gen->getConfig(GenTool::getDir($tableName).'.PageConfig.edit');

        $indexConfig = $gen->withColumnData($indexConfig['fields'], $config['fields']);
        $editConfig = $gen->withColumnData($editConfig['fields'], $config['fields']);

        $string = view('gen.api', compact('indexConfig', 'editConfig', 'config', 'tableName'))->__toString();
        file_put_contents(GenTool::getApiDir($tableName), $string);
    }

    public static function vueSelect($tableName)
    {
        $string = view('gen.select', compact('tableName'))->__toString();
        file_put_contents(GenTool::getSelectDir($tableName), $string);
    }

    public static function vueFormat($tableName)
    {
        $config = (new self())->getConfig(GenTool::getDir($tableName).'.Column');
        $string = view('gen.format', compact('tableName', 'config')) -> __toString();
        file_put_contents(GenTool::getFormatDir($tableName), $string);
    }
}
