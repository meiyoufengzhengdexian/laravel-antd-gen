<?php

namespace App\Console\Commands\Backend;

use App\User;
use Illuminate\Console\Command;

class AdminInitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:init';

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
        $userName = $this->ask('请输入用户名');
        $admin = User::query()->where('name', $userName) ->first();
        if($admin){
            do{
                $confirm = $this->ask('用户名已经存在， 是否覆盖, y/n');
            }while(!in_array($confirm, ['y', 'n']));

            if($confirm == 'n'){
                return;
            }
        }else{
            $admin = new User();
        }

        $admin->name = $userName;
        do{
            $password = $this->ask('请输入密码');
        }while(!$password);
        $admin->password = bcrypt($password);
        $admin->save();

        $this->info('保存成功');
        return;

    }
}
