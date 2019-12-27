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

	Route::get('/files/{_entity}/download', 'FilesController@download')->name('files.download');
	Route::get('/files/{_entity}', 'FilesController@read')->name('files.read');

});

if (config('app.env') !== 'production') {

	//mailcatcher redirect
	Route::get( 'mailcatcher', function () {
		if ( config( 'app.env' ) === 'production' ) {
			abort( 404 );
		} else {
			$ip = gethostbyname( $_SERVER['HTTP_HOST'] );

			return redirect( 'http://' . $ip . ":1080" );
		}
	} );
}
