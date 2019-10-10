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
Route::post('category/{model}', 'MainController@categoryGoods');
Route::post('school', 'MainController@school');
Route::post('school/show/{model}', 'MainController@schoolShow');
Route::post('school/comments/{model}', 'MainController@schoolComments')->where(['model' => '[\d]+']);
Route::post('goods/comments/{model}', 'MainController@goodsComments')->where(['model' => '[\d]+']);

Route::post('goods/{model}','MainController@goods');
Route::post('appoint/{type}','MainController@appoint');

Route::post('notify','NotifyController@index');

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::post('school/comments/{model}/create', 'MainController@schoolCommentsCreate')->where(['model' => '[\d]+']);
    Route::post('goods/comments/{model}/create', 'MainController@goodsCommentsCreate')->where(['model' => '[\d]+']);
    Route::post('school/comments/{model}/zan', 'MainController@schoolCommentsZan')->where(['model' => '[\d]+']);
    Route::post('goods/comments/{model}/zan', 'MainController@goodsCommentsZan')->where(['model' => '[\d]+']);
    Route::post('goods/collect/{model}', 'MainController@goodsCollect')->where(['model' => '[\d]+']);
    Route::post('goods/zan/{model}', 'MainController@goodsZan')->where(['model' => '[\d]+']);

    Route::post('mycollect', 'MainController@mycollect');
    Route::post('mybuy', 'MainController@mybuy');

    Route::post('mkorder/{category}', 'MainController@mkorder');


});
