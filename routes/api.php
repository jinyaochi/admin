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

//https://learnku.com/articles/10885/full-use-of-jwt#852929
//https://learnku.com/articles/9842/user-role-permission-control-package-laravel-permission-usage-description



Route::post('login', 'LoginController@login');
Route::post('refresh', 'LoginController@refresh');

Route::post('index', 'MainController@index');
Route::post('category', 'MainController@category');
Route::post('school', 'MainController@school');

Route::post('goods/{model}','MainController@goods');

Route::group(['middleware' => ['jwt.auth']], function ($api) {

});
