<?php

/*
|--------------------------------------------------------------------------
| Dice Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
Route::group(['prefix' => 'xx','namespace' => 'XucXac'],function(){
    Route::get('/',[
        'as' => 'xx.home',
        'uses' => 'HomeController@index'
    ]);
});