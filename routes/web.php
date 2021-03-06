<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//客户端
//登录
Route::get('/user/login','Login\LoginController@login');
Route::post('/user/login','Login\LoginController@doLogin');
//注册
Route::get('/user/register','Login\LoginController@register');
Route::post('/user/register','Login\LoginController@doRegister');
//退出
Route::get('/user/quit','Login\LoginController@quit');


//app登录
Route::post('/api/login','Login\LoginController@lgn');
