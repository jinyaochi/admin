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



Route::post('mobile/login', 'LoginController@mobile');
Route::post('wx/login', 'LoginController@wx');
Route::post('refresh', 'LoginController@refresh');
Route::post('user', 'LoginController@userinfo');
Route::post('send/code', 'LoginController@code');

Route::post('index', 'MainController@index');
Route::post('category', 'MainController@category');
Route::post('school', 'MainController@school');

Route::post('goods/{model}','MainController@goods');

Route::group(['middleware' => ['jwt.auth']], function ($api) {


});
