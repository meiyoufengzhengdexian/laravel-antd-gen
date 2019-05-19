<?php

namespace App\Console\Commands\Backend;

use App\User;
use Illuminate\Console\Command;

class AdminAddAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:addAuth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '向管理员添加权限';

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
            $adminName = $this->ask("请输入管理员姓名， 昵称");
            $admin = (new User())->findForPassport($adminName);
        } while (!$admin);

        do{
            $permission = $this->ask("请输入权限名");
        }while (!$permission);


        $admin->allow($permission);

        return;
    }
}
