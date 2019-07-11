<?php

namespace App\Console\Commands\Gen;

use App\Service\Gen\GenRequest;
use App\Service\Gen\GenTool;
use Illuminate\Console\Command;

class GenRequestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:request';

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
        do{
            $tableName = $this->ask('请输入表名');
        }while(!$tableName);

        if(!is_dir('./app/Http/Controllers/Backend/' . GenTool::getDir($tableName).'/Request/')){
            mkdir('./app/Http/Controllers/Backend/' . GenTool::getDir($tableName).'/Request/');
        }
        if(file_exists(GenTool::getRequestFile($tableName, 'Index'))){
            do{
                $confirm = $this->ask('已经存在了， 要重新生成吗？ y/n');
            }while(!in_array($confirm, ['y', 'n']));
            if($confirm == 'y'){
                GenRequest::indexRequest($tableName);
                GenRequest::createRequest($tableName);
                GenRequest::showRequest($tableName);
                GenRequest::storeRequest($tableName);
                GenRequest::updateRequest($tableName);
                GenRequest::destroyRequest($tableName);
            }
        }else{
            GenRequest::indexRequest($tableName);
        }
        return;
    }
}
