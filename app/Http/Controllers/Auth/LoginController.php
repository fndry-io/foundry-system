<?php

namespace Foundry\System\Http\Controllers\Auth;

use Foundry\Core\Requests\Response;
use Foundry\System\Http\Requests\LoginRequest;
use Foundry\System\Http\Controllers\Controller;

class LoginController extends Controller {

	/**
	 * Handle Login
	 *
	 * @param LoginRequest $request
	 *
	 * @return Response
	 */
	public function login( LoginRequest $request ) {
		$response = $request->handle( );
		if ( $response->isSuccess() ) {
			if ( ! $url = session()->get( 'url.intended' ) ) {
				//determine if this is a store user
				$scope = $response->getData('user.guard_name');
				if ($scope == 'store') {
					$url = route('store.dashboard');
				} else {
					$url = route( 'home' );
				}
			}
			return Response::success( [ 'redirect' => $url ], __('Welcome!') );
		} else {
			return $response;
		}
	}

	/**
	 * Handle log out
	 *
	 * @return Response
	 */
	public function logout() {

	    if(auth_user()) {
		    UserService::logout();
	    }
		return Response::redirect( route( 'login' ), __('You have been logged out!') );
	}

}
