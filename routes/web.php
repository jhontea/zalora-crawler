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
Route::get('/', 'HomeController@index');
Route::get('detail/{sku}', 'HomeController@show');
Route::post('create', ['uses' => 'HomeController@create', 'as' => 'create']);
Route::post('crawl', 'HomeController@crawl');
Route::get('category', ['uses' => 'HomeController@category', 'as' => 'category-all']);

Route::get('test', ['uses' => 'TestController@index', 'as' => 'test.index']);
