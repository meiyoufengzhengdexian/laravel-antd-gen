<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-6
 * Time: 上午9:36
 */

namespace App\Http\Backend;


use App\Http\Backend\Cate\CateController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Bridge\UserRepository;
use Laravel\Passport\Passport;

class BackendProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $prefix = config('backend.prefix', 'backend');


        Route::group([
            'prefix' => $prefix,
            'middleware' => [
                'backend',
                'scopes:backend'
            ],
            'as' => "$prefix."
        ], function () {
            Route::resource('cate', CateController::class);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}