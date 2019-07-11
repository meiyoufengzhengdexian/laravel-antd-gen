<?php


namespace App\Service\Gen;


class GenController
{
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
}