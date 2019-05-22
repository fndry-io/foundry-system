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

Route::prefix( 'system' )->middleware('web')->group( function () {
	Route::get( 'request/view', 'FormRequestController@view' )->name( 'system.request.view' );
	Route::middleware( 'auth' )->group( function () {
		Route::get( 'request/display', 'FormRequestController@display' )->name( 'system.request.display' );
	});
	if (config('app.debug')) {
		Route::get( 'request/all', 'FormRequestController@all' )->name( 'system.request.all' );
	}
	Route::match(['get', 'post'], 'request/handle', 'FormRequestController@handle' )->name( 'system.request.handle' );
});