<?php

namespace App\Providers;

use App\Http\Backend\BackendBouncer;
use Illuminate\Support\ServiceProvider;

class AdminBouncerProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BackendBouncer::class, function(){
            return new BackendBouncer();
        });
    }
}
