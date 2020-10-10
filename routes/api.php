<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
Route::post('password/reset', 'ResetPasswordController@reset');
//Route::post('password/byemail', 'AuthController@getUserByToken');


Route::group([
    'middleware' => 'apiJWT',
    'prefix' => 'auth'
], function () {
    Route::get('user', 'AuthController@getAuthUser');
    Route::post('logout', 'AuthController@logout');
    Route::post('update/user', 'UserController@update');
    Route::post('delete/user', 'UserController@delete');

    Route::group([
        'middleware' => 'isAdmin',
        'prefix' => 'admin'
    ], function () {
        Route::get('users/{request?}/{name?}/{email?}', 'UserController@index');
        Route::post('show/user', 'UserController@show');
        Route::post('delete/permanent/user', 'UserController@destroy');
        Route::post('restore/user', 'UserController@restore');
    });
});
