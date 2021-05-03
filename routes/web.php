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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/export', 'App\Http\Controllers\ImportController@export')->name('export');
Route::get('/importExportView', 'App\Http\Controllers\ImportController@importExportView');
Route::post('/import', 'App\Http\Controllers\ImportController@import')->name('import');
