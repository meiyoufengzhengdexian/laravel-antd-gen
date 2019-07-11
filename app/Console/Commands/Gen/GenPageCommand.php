<?php

namespace App\Console\Commands\Gen;

use App\Service\Gen\GenPageConfig;
use App\Service\Gen\GenTool;
use Illuminate\Console\Command;

class GenPageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:pageConfig';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @throws \App\Service\Gen\Exception\ConfigNotFoundException
     */
    public function handle()
    {
        do {
            $tableName = $this->ask('请输入表命');
        } while (!$tableName);

        if (file_exists(GenTool::getPageConfigFile($tableName, 'Index'))) {
            do {
                $confirm = $this->ask('视图配置文件已经存在，是否重新生成? y/n');
            } while (!in_array($confirm, ['y', 'n']));

            if ($confirm == 'y') {
                GenPageConfig::index($tableName, $this);
            }
        } else {
            GenPageConfig::index($tableName, $this);
        }
    }
}
