<?php

namespace App\Console\Commands\Gen;

use App\Service\Gen\GenPermission;
use App\Service\Gen\GenTool;
use Illuminate\Console\Command;

class GenPermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成基本权限';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        do {
            $tableName = $this->ask('请输入表名');
        } while (!$tableName);


        if (!is_dir('./app/Http/Controllers/Backend/' . GenTool::getDir($tableName) . '/AuthConfig')) {
            mkdir('./app/Http/Controllers/Backend/' . GenTool::getDir($tableName) . '/AuthConfig');
        }

        if (file_exists(GenTool::getPermissionFile($tableName))) {
            do {
                $confirm = $this->ask('权限已经存在是否重新生成 y/n');
            } while (!in_array($confirm, ['y', 'n']));

            if ($confirm == 'y') {
                GenPermission::gen($tableName);
            }
        } else {
            GenPermission::gen($tableName);
        }
        return true;
    }
}
