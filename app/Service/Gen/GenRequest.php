<?php


namespace App\Service\Gen;


class GenRequest
{
    public static function indexRequest($table)
    {
        $indexRequestString = "<?php\n";
        $indexRequestString .= view('gen.indexRequest', compact('table'));
        file_put_contents(GenTool::getRequestFile($table, 'Index'), $indexRequestString);
    }

    public static function createRequest($table)
    {
        $requestString = "<?php\n";
        $requestString .= view('gen.createRequest', compact('table'));
        file_put_contents(GenTool::getRequestFile($table, 'Create'), $requestString);
    }

    public static function storeRequest($table)
    {
        $requestString = "<?php\n";
        $requestString .= view('gen.storeRequest', compact('table'));
        file_put_contents(GenTool::getRequestFile($table, 'Store'), $requestString);
    }
    public static function showRequest($table)
    {
        $requestString = "<?php\n";
        $requestString .= view('gen.showRequest', compact('table'));
        file_put_contents(GenTool::getRequestFile($table, 'Show'), $requestString);
    }

    public static function updateRequest($table)
    {
        $requestString = "<?php\n";
        $requestString .= view('gen.updateRequest', compact('table'));
        file_put_contents(GenTool::getRequestFile($table, 'Update'), $requestString);
    }

    public static function destroyRequest($table)
    {
        $requestString = "<?php\n";
        $requestString .= view('gen.destroyRequest', compact('table'));
        file_put_contents(GenTool::getRequestFile($table, 'Destroy'), $requestString);
    }
}