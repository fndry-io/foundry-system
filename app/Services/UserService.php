<?php

namespace Foundry\System\Services;

use Carbon\Carbon;
use Foundry\Core\Auth\TokenGuard;
use Foundry\Core\Entities\Contracts\HasApiToken;
use Foundry\Core\Entities\Contracts\IsUser;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\System\Events\UserLoggedIn;
use Foundry\System\Events\UserLoggedOut;
use Foundry\System\Inputs\User\ForgotPasswordInput;
use Foundry\System\Inputs\User\ResetPasswordInput;
use Foundry\System\Inputs\User\UserEditInput;
use Foundry\System\Inputs\User\UserInput;
use Foundry\System\Inputs\User\UserLoginInput;
use Foundry\System\Inputs\User\UserRegisterInput;
use Foundry\System\Models\User;
use Foundry\System\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;

class UserService extends BaseService {

	/**
	 * Browse Users
	 *
	 * @param Inputs $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Response
	 */
	public function browse( Inputs $inputs, $page = 1, $perPage = 20, $sortBy = null, $sortDesc = null ): Response {
		return Response::success(UserRepository::repository()->browse($inputs->values(), $page, $perPage, $sortBy, $sortDesc));
	}

	/**
	 * @param UserLoginInput $input
	 *
	 * @return Response
	 */
	public function login(UserLoginInput $input) : Response
	{
		$inputs = $input->values();

		/**
		 * @var Guard|StatefulGuard $guard
		 */
		$guard = Auth::guard($input->guard);

		//if current logged in user, log them out first
		if ($guard->check() && $guard instanceof StatefulGuard) {
			$guard->logout();
		}

		/**
		 * @var UserProvider $provider
		 */
		$provider = $guard->getProvider();

		/**
		 * @var $user User
		 */
		if($user = $provider->retrieveByCredentials([
			'email' => $inputs['email']
		])){
			if ($provider->validateCredentials($user, $inputs)) {
				//detect if the user is longer active
				if ($user->isActive()) {
				    event(new UserLoggedIn($user));
					$guard->setUser($user);
					return $this->returnGuardUser($guard);
				} else {
					return Response::error(__("Account no longer active, please contact the Support Team"), 403);
				}
			}
		}
		return Response::error(__("Permission denied, wrong password and username combination"), 401);
	}

	/**
	 * Log the user out of the given guard
	 *
	 * If no guard is supplied, it will use the system default
	 *
	 * @param null $guard
	 *
	 * @return Response
	 */
	public function logout($guard = null)
	{
		$guard = Auth::guard($guard);
		if ($guard->check()) {

            /**
             * @var User $user
             */
            $user = $guard->user();

            //if the guard is an api guard, remove the token
			if ($guard instanceof TokenGuard) {
				$guard->clearToken($user);
				UserRepository::repository()->save($user);
			}

			//if current logged in user, log them out first
			if ($guard instanceof StatefulGuard) {
				Auth::logout();
				Session::invalidate();
			}

            event(new UserLoggedOut($user));
		}
		return Response::success();
	}

	/**
	 * @param Guard $guard The guard which contains the user to return
	 * @param Boolean $setToken Generate or Refresh the token assigned to the user
	 *
	 * @return Response
	 */
	public function returnGuardUser(Guard $guard = null, $setToken = true) : Response
	{
		/**
		 * @var $user IsUser
		 */
		$user = $guard->user();
		$user->logged_in = true;
		$user->last_login_at = new Carbon();

		$data = [
			'user' => $user->only(['id', 'uuid', 'username', 'display_name', 'email', 'is_super_admin', 'settings', 'profile_url'])
		];
        $data['user']['is_admin'] = $user->isAdmin();
		$data['user']['is_super_admin'] = $user->isSuperAdmin();
        $data['user']['abilities'] = $user->getAllPermissions()->pluck('name');

		if ($guard instanceof TokenGuard && $user instanceof HasApiToken) {

			if ($setToken) {
				$token = $guard->setToken($user);
			} else {
				$token = $guard->getToken($user);
			}
			$data['token'] = $token;
		}

		UserRepository::repository()->save($user);

		return Response::success($data);
	}

	/**
	 * @param UserRegisterInput|Inputs $input
	 *
	 * @return Response
	 */
	public function register(UserRegisterInput $input) : Response
	{
		$user = UserRepository::repository()->register($input->values());
		if ($user) {
			return Response::success($user);
		} else {
			return Response::error(__("Unable to register user"), 500);
		}
	}

	/**
	 * Request link to reset password
	 *
	 * @param ForgotPasswordInput $input
	 * @return Response
	 */
	public function forgotPassword(ForgotPasswordInput $input)
	{
		$response = $this->broker()->sendResetLink(
			$input->toArray()
		);

		if ($response == Password::RESET_LINK_SENT){
			return Response::success([], __('Please check your email for reset instructions.'));
		}
		else{
			$user = UserRepository::repository()->findOneBy(['email' => $input->email]);
			return $user
				? Response::error(__("Requested resource was not found"), 400)
				: Response::error(__("Account with provided E-mail address not found!"), 404);
		}
	}

	/**
	 * Reset Password
	 *
	 * @param ResetPasswordInput $input
	 * @return Response
	 */
	public function resetPassword(ResetPasswordInput $input)
	{
		$response = $this->broker()->reset( $input->toArray(), function ($user, $password) {
			return UserRepository::repository()->resetPassword($user, $password);
		} );

		if($response === Password::PASSWORD_RESET){
			return Response::success();
		} else {
			return Response::error(__("Account with provided E-mail address not found!"), 404);
		}
	}


	/**
	 * @param UserInput|Inputs $input
	 *
	 * @return Response
	 */
	public function add(UserInput $input) : Response
	{
		$user = UserRepository::repository()->insert($input->values());
		if ($user) {
			return Response::success($user);
		} else {
			return Response::error(__('Unable to add user'), 500);
		}
	}

	/**
	 * @param UserEditInput|Inputs $input
	 * @param IsUser $user
	 *
	 * @return Response
	 */
	public function edit(UserEditInput $input, IsUser $user) : Response
	{
		$user = UserRepository::repository()->update($user, $input->values());
		if ($user) {
			return Response::success($user);
		} else {
			return Response::error(__('Unable to update user'), 500);
		}
	}

	/**
	 * Delete a user
	 *
	 * @param IsUser $user
	 *
	 * @return Response
	 */
	public function delete(IsUser $user) : Response
	{
		if ($user->isSuperAdmin()) {
			return Response::error(__('You cannot delete a Super User'), 408);
		}
		try {
			UserRepository::repository()->delete($user);
			return Response::success();
		} catch (\Throwable $e) {
			return Response::error(__('Could not delete the user'), 500);
		}
	}

	/**
	 * Delete a user
	 *
	 * @param IsUser $user
	 *
	 * @return Response
	 * @throws \Exception
	 */
	public function restore(IsUser $user) : Response
	{
		$user = UserRepository::repository()->restore($user);
		if($user) {
			return Response::success();
		} else {
			return Response::error(__('Unable to restore user'), 500);
		}

	}


	/**
	 * Get the broker to be used during password reset.
	 *
	 * @return \Illuminate\Contracts\Auth\PasswordBroker
	 */
	public function broker()
	{
		return Password::broker();
	}

	/**
	 * Sync the users settings to the database
	 *
	 * @param IsUser $user
	 * @param $settings
	 *
	 * @return Response
	 */
	public function syncSettings(IsUser $user, array $settings = [])
	{
		$user = UserRepository::repository()->syncSettings($user, $settings);
		if($user) {
			return Response::success();
		} else {
			return Response::error(__('Unable to sync user settings'), 500);
		}
	}



}
