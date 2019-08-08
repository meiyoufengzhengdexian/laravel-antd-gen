<?php

namespace App\Console\Commands\Gen;

use App\Service\Gen\GenController;
use App\Service\Gen\GenTool;
use Illuminate\Console\Command;

class GenControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '根据表名生成控制器';

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
            $tableName = $this->ask('请输入表命');
        } while (!$tableName);

        if (file_exists(GenTool::getControllerFile($tableName))) {
            do {
                $confirm = $this->ask('控制器已经存在， 是否覆盖? y/n');
            } while (!in_array($confirm, ['y', 'n']));

            if ($confirm == 'y') {
                GenController::crud($tableName);
                GenController::controller($tableName);
            }
        } else {
            GenController::crud($tableName);
            GenController::controller($tableName);
        }

        return true;
    }
}
