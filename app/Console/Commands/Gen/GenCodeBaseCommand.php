<?php

namespace App\Console\Commands\Gen;

use App\Service\Gen\GenBase;
use App\Service\Gen\GenTool;
use Illuminate\Console\Command;

class GenCodeBaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:base';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '输入表名称， 生成配置信息';

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
     * @throws \App\Service\Gen\Exception\ConfigNotFoundException
     * @throws \Throwable
     */
    public function handle()
    {
        do {
            $tableName = $this->ask("请输入表名称:");
        } while (!$tableName);

        if (file_exists(GenTool::getTableConfigFile($tableName))) {
            do {
                $confirm = $this->ask("要从新生成表数据结构吗? y/n");
            } while (!in_array($confirm, ['y', 'n']));
            if ($confirm == 'y') {
                do {
                    $title = $this->ask("请输入表中文名:");
                } while (!$title);

                do {
                    $desc = $this->ask("请输入表描述:");
                } while (!$desc);

                GenBase::genBaseConfig($tableName, $title, $desc);
                GenBase::genTableConfig($tableName, $this);
            }
        } else {
            do {
                $title = $this->ask("请输入表中文名:");
            } while (!$title);

            do {
                $desc = $this->ask("请输入表描述:");
            } while (!$desc);

            GenBase::genBaseConfig($tableName, $title, $desc);
            GenBase::genTableConfig($tableName, $this);
        }


        if (file_exists(GenTool::getModelFile($tableName))) {
            do {
                $confirm = $this->ask("要从新生成表模型类吗? y/n");
            } while (!in_array($confirm, ['y', 'n']));
            if ($confirm == 'y') {
                GenBase::genModelConfig($tableName, $this);
            }
        } else {
            GenBase::genModelConfig($tableName, $this);
        }
    }
}
