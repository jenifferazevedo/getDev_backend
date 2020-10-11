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

Route::get('locations', 'LocationController@index');
Route::get('companies/{name?}/{location?}', 'CompanyController@indexQuery');


Route::group([
    'middleware' => 'apiJWT',
    'prefix' => 'auth'
], function () {
    Route::get('user', 'AuthController@getAuthUser');
    Route::post('logout', 'AuthController@logout');
    Route::post('update/user', 'UserController@update');
    Route::post('delete/user', 'UserController@delete');
    Route::get('locations', 'LocationController@index');
    Route::get('internship-types', 'InternshipTypeController@index');
    Route::get('knowledge-area', 'KnowledgeAreaController@index');

    Route::get('user/companies', 'CompanyController@indexByUser');
    Route::post('company/store', 'CompanyController@store');

    Route::group([
        'middleware' => 'isAdmin',
        'prefix' => 'admin'
    ], function () {
        Route::post('users/{type?}', 'UserController@index');
        Route::post('show/user', 'UserController@show');
        Route::post('delete/permanent/user', 'UserController@destroy');
        Route::post('restore/user', 'UserController@restore');

        Route::get('locations/{request?}/{name?}', 'LocationController@indexQuery');
        Route::post('location/store', 'LocationController@store');
        Route::post('location/update', 'LocationController@update');
        Route::post('location/show', 'LocationController@show');
        Route::post('location/delete', 'LocationController@delete');
        Route::post('location/restore', 'LocationController@restore');
        Route::post('location/delete/permanent', 'LocationController@destroy');

        Route::get('internship-types/{request?}/{name?}', 'InternshipTypeController@indexQuery');
        Route::post('internship-type/store', 'InternshipTypeController@store');
        Route::post('internship-type/update', 'InternshipTypeController@update');
        Route::post('internship-type/show', 'InternshipTypeController@show');
        Route::post('internship-type/delete', 'InternshipTypeController@delete');
        Route::post('internship-type/restore', 'InternshipTypeController@restore');
        Route::post('internship-type/delete/permanent', 'InternshipTypeController@destroy');

        Route::get('knowledge-areas/{request?}/{name?}', 'KnowledgeAreaController@indexQuery');
        Route::post('knowledge-area/store', 'KnowledgeAreaController@store');
        Route::post('knowledge-area/update', 'KnowledgeAreaController@update');
        Route::post('knowledge-area/show', 'KnowledgeAreaController@show');
        Route::post('knowledge-area/delete', 'KnowledgeAreaController@delete');
        Route::post('knowledge-area/restore', 'KnowledgeAreaController@restore');
        Route::post('knowledge-area/delete/permanent', 'KnowledgeAreaController@destroy');
    });
});
