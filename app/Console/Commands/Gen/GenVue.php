<?php

namespace App\Console\Commands\Gen;

use App\Service\Gen\GenController;
use Illuminate\Console\Command;

class GenVue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:vue';

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
     */
    public function handle()
    {
        do {
            $tableName = $this->ask('请输入表名');
        } while (!$tableName);

        GenController::vueFormat($tableName);
        GenController::vueApi($tableName);
        GenController::vueSelect($tableName);
        GenController::vueIndex($tableName);

    }
}
