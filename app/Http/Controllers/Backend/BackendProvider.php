<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-6
 * Time: 上午9:36
 */

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Backend\Cate\CateController;
use App\Http\Controllers\BackendLoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;


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

            ],
            'as' => "$prefix.",

        ], function () {
            Route::group([
                'middleware'=> [
                    'backend',
                    'scopes:backend'
                ]
            ], function(){
                Route::resource('cate', CateController::class);
                Route::get('currentUser', BackendLoginController::class."@currentUser");
            });
            Route::post('login', BackendLoginController::class."@login");
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