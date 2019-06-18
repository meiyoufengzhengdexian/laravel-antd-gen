<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-6
 * Time: 上午9:36
 */

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Backend\Cate\CateController;
use App\Http\Controllers\Backend\City\CityController;
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