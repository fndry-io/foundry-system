<?php

use Illuminate\Support\Facades\Route;
use Foundry\Core\Facades\FormRequestHandler;

Route::prefix( 'system' )->middleware('web')->group( function () {
	if (config('app.debug')) {
		Route::get( 'request/all', 'FormRequestController@all' )->name( 'system.request.all' );
	}
});

//auth
Route::match(['GET', 'POST'], '/auth/login',    'AuthController@login')->name('foundry.system.auth.login');
Route::match(['GET', 'POST'], '/auth/forgot',   'AuthController@forgotPassword')->name('foundry.system.auth.forgot_password');
Route::match(['GET', 'POST'], '/auth/reset',    'AuthController@resetPassword')->name('foundry.system.auth.reset_password');
Route::match(['GET', 'POST'], '/auth/register', 'UserController@register')->name('foundry.system.auth.register');
Route::match(['GET', 'POST'], '/auth/logout',   'AuthController@logout')->name('foundry.system.auth.logout');

Route::middleware('auth:system')->group( function () {
	//auth user
	Route::match(['GET', 'POST'], '/auth/edit', 'AuthController@edit' )->name('foundry.system.auth.edit');
    FormRequestHandler::route( '/auth/profile', 'Foundry\System\Http\Requests\Auth\UploadProfileImageRequest' );
    FormRequestHandler::route( '/auth/profile/delete', 'Foundry\System\Http\Requests\Auth\DeleteProfileImageRequest' );
	Route::get('/auth/user',       'AuthController@readUser')->name('foundry.system.auth.user');
	Route::post('/auth/settings',  'AuthController@syncUserSettings')->name('foundry.system.auth.sync-settings');
});

Route::prefix('system')->middleware('auth:system')->group( function () {

	//users
	Route::get(                    '/users',                     'UsersController@browse')->name('foundry.system.users.browse');
	Route::match(['GET', 'POST'],  '/users/add',                 'UsersController@add')->name('foundry.system.users.add');
	Route::post(                   '/users/add-bulk',            'UsersController@bulkAdd')->name('foundry.system.users.add.bulk');
	Route::get(                    '/users/list',                'UsersController@list')->name('foundry.system.users.list');
	Route::match(['GET', 'POST'],  '/users/{_entity}/edit',      'UsersController@edit')->name('foundry.system.users.edit');
	Route::post(                   '/users/{_entity}/delete',    'UsersController@delete')->name('foundry.system.users.delete');
	Route::post(                   '/users/{_entity}/restore',   'UsersController@restore')->name('foundry.system.users.restore');
	//FormRequestHandler::route('/users/{_entity}',       'Foundry\System\Http\Requests\Users\ReadUserRequest');

	//roles
    Route::get(                   '/roles',                 'RolesController@browse')->name('foundry.system.roles.browse');
    Route::match(['GET', 'POST'], '/roles/add',             'RolesController@add')->name('foundry.system.roles.add');
    Route::match(['GET', 'POST'], '/roles/{_entity}/edit',  'RolesController@edit')->name('foundry.system.roles.edit');
    Route::post(                  '/roles/{_entity}/delete','RolesController@delete')->name('foundry.system.roles.delete');
    Route::post(                  '/roles/permissions/edit',        'RolesController@editPermissions')->name('foundry.system.roles.edit-permissions');
    Route::get(                   '/roles/permissions/{guard}',     'RolesController@readPermissions')->name('foundry.system.roles.read-permissions');

    //settings
    FormRequestHandler::route('/settings',                      'Foundry\System\Http\Requests\Settings\BrowseSettingsRequest');
    FormRequestHandler::route('/settings/{_entity}/edit',          'Foundry\System\Http\Requests\Settings\EditSettingRequest');

    // permissions
    Route::post('/permissions/sync', 'PermissionsController@sync');

	//PickList Items
	FormRequestHandler::route('/pick-list-items/add',             'Foundry\System\Http\Requests\PickListItems\AddPickListItemRequest');
	FormRequestHandler::route('/pick-list-items/{_entity}/edit',  'Foundry\System\Http\Requests\PickListItems\EditPickListItemRequest');

    //PickList
    Route::get(                     '/pick-lists',                      'PickListsController@browse')->name('foundry.system.pick-lists.browse');
    Route::match(['GET', 'POST'],   '/pick-lists/add',                  'PickListsController@add')->name('foundry.system.pick-lists.add');
    Route::match(['GET', 'POST'],   '/pick-lists/{_entity}/edit',       'PickListsController@edit')->name('foundry.system.pick-lists.edit');
    Route::get(                     '/pick-lists/{_entity}',            'PickListsController@read')->name('foundry.system.pick-lists.read');
    Route::get(                     '/pick-lists/{_entity}/list',       'PickListsController@listItem')->name('foundry.system.pick-lists.items.list');
    Route::match(['GET', 'POST'],   '/pick-lists/{_entity}/items/add',  'PickListsController@addItem')->name('foundry.system.pick-lists.items.add');
    Route::get(                     '/pick-lists/{_entity}/items',      'PickListsController@browseItem')->name('foundry.system.pick-lists.items.browse');

	//Files
	FormRequestHandler::route('/files/upload',                 'Foundry\System\Http\Requests\Files\UploadFileRequest');
	FormRequestHandler::route('/files/upload/image',           'Foundry\System\Http\Requests\Files\UploadImageFileRequest');
	FormRequestHandler::route('/files/browse',                 'Foundry\System\Http\Requests\Files\BrowseFilesRequest');
	FormRequestHandler::route('/files/{_entity}/delete',       'Foundry\System\Http\Requests\Files\DeleteFileRequest');

	Route::get('/files/{_entity}/download', 'FilesController@download');
	Route::get('/files/{_entity}',          'FilesController@read');

	//Folders
	FormRequestHandler::route('/folders/{_entity}/edit',    'Foundry\System\Http\Requests\Folders\EditFolderRequest');
	FormRequestHandler::route('/folders/{_entity}/delete',  'Foundry\System\Http\Requests\Folders\DeleteFolderRequest');
	FormRequestHandler::route('/folders/{_entity}/restore', 'Foundry\System\Http\Requests\Folders\RestoreFolderRequest');
	FormRequestHandler::route('/folders/{_entity}',         'Foundry\System\Http\Requests\Folders\ReadFolderRequest');
	FormRequestHandler::route('/folders/{_entity}/browse',  'Foundry\System\Http\Requests\Folders\BrowseFolderRequest');
	FormRequestHandler::route('/folders/{_entity}/add-file','Foundry\System\Http\Requests\Folders\AddFileRequest');
	FormRequestHandler::route('/folders/{_entity}/add-folder','Foundry\System\Http\Requests\Folders\AddFolderRequest');

    FormRequestHandler::route('/activities',        'Foundry\System\Http\Requests\Activities\BrowseActivityRequest');

});

