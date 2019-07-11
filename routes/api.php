<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$prefix = config('backend.prefix', 'backend');

Route::group([
    'prefix' => $prefix,
    'as' => "$prefix.",

], function () {

    Route::group([
        'namespace' => 'Backend'
    ], function () {

        Route::group([
        ], function () {
            Route::post('login', 'BackendLoginController@login');
        });

        Route::group([
            'middleware' => [
                'backend',
                'scopes:backend'
            ],
        ], function () {
            Route::resource('cate', 'Cate\CateController');
            Route::resource('test', 'Cate\TestController');
            Route::get('currentUser', 'BackendLoginController@currentUser');
            Route::get('cityOptions', 'City\CityController@options');
            Route::get('cateOptions', 'Cate\CateController@cateOptions');
            Route::get('tagOptions', 'Cate\CateController@tags');
        });
    });
});