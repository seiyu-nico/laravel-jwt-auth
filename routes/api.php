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

Route::post('login', 'Api\LoginController@login');
Route::group(['middleware' => ['jwt.auth']], function () {
    Route::post('logout', 'Api\LoginController@logout');
    Route::get('refresh', 'Api\LoginController@refresh');
    Route::get('user', 'Api\UserController@show');
});