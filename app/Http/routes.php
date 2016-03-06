<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => 'api'], function()
{
    Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
    Route::post('authenticate', 'AuthenticateController@authenticate');

});
Route::group(['prefix'=>'api', 'before' => 'jwt-auth', 'after' => 'jwt.refresh'],function(){
    Route::resource('user', 'UserController');
    Route::resource('product', 'ProductController');
    Route::resource('order', 'OrderController');
    Route::resource('dailytransaction', 'DailyTransactionController');
});