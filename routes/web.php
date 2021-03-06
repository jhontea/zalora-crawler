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
Route::get('/', ['uses' => 'HomeController@index', 'as' => 'index']);

Route::any('search', ['uses' => 'SearchController@search', 'as' => 'search']);

Route::get('category/{category}', ['uses' => 'HomeController@category', 'as' => 'category']);
Route::get('detail/{sku}', ['uses' => 'HomeController@show', 'as' => 'show']);

Route::post('crawl', ['uses' => 'HomeController@crawl', 'as' => 'crawl']);
Route::post('create', ['uses' => 'HomeController@create', 'as' => 'create']);

Route::get('test', ['uses' => 'TestController@index', 'as' => 'test.index']);
