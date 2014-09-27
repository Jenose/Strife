<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('c/strife/manifest/product/strife/branch/prod/buildType/client/os/windows/arch/x86/version/live', 'CController@getManifest');
Route::controller('c', 'CController');
Route::controller('/', 'HomeController');
