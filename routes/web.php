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

Auth::routes();
Route::post('/mypage/{id}','PlaylistController@mypage')->name('mypage');
Route::post('/mypage/show/{id}','PlaylistController@show')->name('mypage.show');
Route::post('/playlists','PlaylistController@store')->name('playlists.store');
Route::get('/playlists','PlaylistController@index')->name('playlists.index');
Route::post('/playlists/{id}','PlaylistController@update')->name('playlists.update');

Route::get('/home', 'HomeController@index')->name('home');

