<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'CharacterController@index')->name('characters.index');
Route::get('/{id}', 'CharacterController@show')->name('characters.show');
Route::get('/error', 'CharacterController@error')->name('characters.error');

