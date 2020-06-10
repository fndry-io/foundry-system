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

Route::prefix('system')->group( function () {

	Route::get('/files/{_entity}/download', 'FilesController@download')->name('system.files.download');
	Route::get('/files/{_entity}', 'FilesController@read')->name('system.files.read');

});

if (config('app.env') !== 'production') {
	Route::get( 'mailcatcher', 'SystemController@mailcatcher' );
}
