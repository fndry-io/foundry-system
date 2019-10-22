<?php

use Illuminate\Support\Facades\Route;
use Foundry\Core\Facades\FormRequestHandler;

Route::prefix( 'system' )->middleware('web')->group( function () {
	if (config('app.debug')) {
		Route::get( 'request/all', 'FormRequestController@all' )->name( 'system.request.all' );
	}
});

//auth
FormRequestHandler::route('/auth/login',                'Foundry\System\Http\Requests\Auth\LoginRequest');
FormRequestHandler::route('/auth/logout',               'Foundry\System\Http\Requests\Auth\LogoutRequest');
FormRequestHandler::route('/auth/forgot',               'Foundry\System\Http\Requests\Auth\ForgotPasswordRequest');
FormRequestHandler::route('/auth/reset',                'Foundry\System\Http\Requests\Auth\ResetPasswordRequest');
FormRequestHandler::route('/auth/register',             'Foundry\System\Http\Requests\Users\RegisterUserRequest');

Route::middleware('auth:api')->group( function () {
	//auth user
	FormRequestHandler::route( '/auth/edit', 'Foundry\System\Http\Requests\Auth\EditUserRequest' );
    FormRequestHandler::route( '/auth/profile', 'Foundry\System\Http\Requests\Auth\UploadProfileImageRequest' );
	FormRequestHandler::route( '/auth/user', 'Foundry\System\Http\Requests\Auth\ReadUserRequest' );
	FormRequestHandler::route( '/auth/settings', 'Foundry\System\Http\Requests\Auth\SyncUserSettingsRequest' );
});

Route::prefix('system')->middleware('auth:api')->group( function () {

	//users
	FormRequestHandler::route('/users',                 'Foundry\System\Http\Requests\Users\BrowseUsersRequest');
	FormRequestHandler::route('/users/add',             'Foundry\System\Http\Requests\Users\AddUserRequest');
	FormRequestHandler::route('/users/add-bulk',             'Foundry\System\Http\Requests\Users\BulkAddUserRequest');
	FormRequestHandler::route('/users/list',            'Foundry\System\Http\Requests\Users\ListUsersRequest');
	FormRequestHandler::route('/users/{_entity}/edit',  'Foundry\System\Http\Requests\Users\EditUserRequest');
	FormRequestHandler::route('/users/{_entity}/delete','Foundry\System\Http\Requests\Users\DeleteUserRequest');
	FormRequestHandler::route('/users/{_entity}/restore','Foundry\System\Http\Requests\Users\RestoreUserRequest');
	//FormRequestHandler::route('/users/{_entity}',       'Foundry\System\Http\Requests\Users\ReadUserRequest');

	//roles
	FormRequestHandler::route('/roles',                 'Foundry\System\Http\Requests\Roles\BrowseRolesRequest');
	FormRequestHandler::route('/roles/add',             'Foundry\System\Http\Requests\Roles\AddRoleRequest');
	FormRequestHandler::route('/roles/{_entity}/edit',  'Foundry\System\Http\Requests\Roles\EditRoleRequest');
	FormRequestHandler::route('/roles/{_entity}/delete','Foundry\System\Http\Requests\Roles\DeleteRoleRequest');

	//PickList Items
	FormRequestHandler::route('/pick-list-items/add',             'Foundry\System\Http\Requests\PickListItems\AddPickListItemRequest');
	FormRequestHandler::route('/pick-list-items/{_entity}/edit',  'Foundry\System\Http\Requests\PickListItems\EditPickListItemRequest');

    //PickList
    FormRequestHandler::route('/pick-lists',                 'Foundry\System\Http\Requests\PickLists\BrowsePickListsRequest');
    FormRequestHandler::route('/pick-lists/add',             'Foundry\System\Http\Requests\PickLists\AddPickListRequest');
    FormRequestHandler::route('/pick-lists/{_entity}/edit',  'Foundry\System\Http\Requests\PickLists\EditPickListRequest');
	FormRequestHandler::route('/pick-lists/{_entity}/list', 'Foundry\System\Http\Requests\PickLists\ListPickListItemsRequest');
	FormRequestHandler::route('/pick-lists/{_entity}/items/add', 'Foundry\System\Http\Requests\PickLists\AddPickListItemRequest');
	FormRequestHandler::route('/pick-lists/{_entity}/items', 'Foundry\System\Http\Requests\PickLists\BrowsePickListItemsRequest');
    FormRequestHandler::route('/pick-lists/{_entity}',       'Foundry\System\Http\Requests\PickLists\ReadPickListRequest');


	//Files
	FormRequestHandler::route('/files/upload',                 'Foundry\System\Http\Requests\Files\UploadFileRequest');
	FormRequestHandler::route('/files/upload/image',           'Foundry\System\Http\Requests\Files\UploadImageFileRequest');
	FormRequestHandler::route('/files/browse',                 'Foundry\System\Http\Requests\Files\BrowseFilesRequest');
	FormRequestHandler::route('/files/{_entity}/delete',       'Foundry\System\Http\Requests\Files\DeleteFileRequest');

	Route::get('/files/{_entity}/download', 'FilesController@download');
	Route::get('/files/{_entity}',          'FilesController@read');

	//Folders
	FormRequestHandler::route('/folders/{_entity}/edit',  'Foundry\System\Http\Requests\Folders\EditFolderRequest');
	FormRequestHandler::route('/folders/{_entity}/delete','Foundry\System\Http\Requests\Folders\DeleteFolderRequest');
	FormRequestHandler::route('/folders/{_entity}/restore','Foundry\System\Http\Requests\Folders\RestoreFolderRequest');
	FormRequestHandler::route('/folders/{_entity}',       'Foundry\System\Http\Requests\Folders\ReadFolderRequest');
	FormRequestHandler::route('/folders/{_entity}/browse','Foundry\System\Http\Requests\Folders\BrowseFolderRequest');
	FormRequestHandler::route('/folders/{_entity}/add-file','Foundry\System\Http\Requests\Folders\AddFileRequest');
	FormRequestHandler::route('/folders/{_entity}/add-folder','Foundry\System\Http\Requests\Folders\AddFolderRequest');

    FormRequestHandler::route('/activities',        'Foundry\System\Http\Requests\Activities\BrowseActivityRequest');

});

