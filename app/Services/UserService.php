<?php

namespace Foundry\System\Services;

use Carbon\Carbon;
use Foundry\Core\Auth\TokenGuard;
use Foundry\Core\Entities\Contracts\HasApiToken;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\System\Inputs\User\ForgotPasswordInput;
use Foundry\System\Inputs\User\ResetPasswordInput;
use Foundry\System\Inputs\User\UserEditInput;
use Foundry\System\Inputs\User\UserInput;
use Foundry\System\Inputs\User\UserLoginInput;
use Foundry\System\Inputs\User\UserRegisterInput;
use Foundry\System\Models\User;
use Foundry\System\Repositories\UserRepository;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Eloquent\Builder;
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
	public function browse( Inputs $inputs, $page = 1, $perPage = 20 ): Response {
		return Response::success(UserRepository::repository()->filter(function(Builder $qb) use ($inputs) {

			$qb
				->select('*')
				->orderBy('display_name', 'ASC');

			if ($search = $inputs->value('search', null)) {
				$qb->where(function(Builder $qb) use ($search) {
					$qb->where('username', 'like', "%".$search."%");
					$qb->where('display_name', 'like', "%".$search."%");
					$qb->where('email', 'like', "%".$search."%");
				});
			}

			$deleted = $inputs->value('deleted', 'undeleted');
			if ($deleted == 'deleted') {
				$qb->onlyTrashed();
			}

			return $qb;

		}, $page, $perPage));
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

			//if the guard is an api guard, remove the token
			if ($guard instanceof TokenGuard) {
				/**
				 * @var User $user
				 */
				$user = $guard->user();
				$guard->clearToken($user);
				UserRepository::repository()->save($user);
			}

			//if current logged in user, log them out first
			if ($guard instanceof StatefulGuard) {
				Auth::logout();
				Session::invalidate();
			}

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
		 * @var $user User
		 */
		$user = $guard->user();
		$user->logged_in = true;
		$user->last_login_at = new Carbon();

		$data = [
			'user' => $user->only(['id', 'uuid', 'username', 'display_name', 'email', 'is_super_admin', 'settings'])
		];
		$data['user']['is_super_admin'] = $user->isSuperAdmin();

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
		$user = new User($input->values());
		$user->active = true;
		$user->password = $input->password;
		UserRepository::repository()->save($user);
		return Response::success($user);
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
			return $user? Response::error(__("Requested resource was not found"), 400)
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
			/**
			 * @var $user User
			 */
			$user->password = $password;
			UserRepository::repository()->save($user);
			event(new PasswordReset($user));
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
		$user = new User();
		$user->fill($input->values());

		if ($input->password) {
			$user->password = $input->password;
		}

		if (auth_user()->isSuperAdmin()) {

			//todo change to control this in the form
			$user->active = true;

			if ($input->super_admin === true) {
				$user->super_admin = true;
			} else {
				$user->super_admin = false;
			}
		}

		if ($input->supervisor) {
			if ($supervisor = UserRepository::repository()->find($input->supervisor)) {
				$user->supervisor = $supervisor;
			}
		}

		UserRepository::repository()->save($user);
		return Response::success($user);
	}

	/**
	 * @param UserEditInput|Inputs $input
	 * @param User $user
	 *
	 * @return Response
	 */
	public function edit(UserEditInput $input, User $user) : Response
	{
		$user->fill($input->values());
		if ($input->password) {
			$user->password = $input->password;
		}
		if (Auth::user()->isSuperAdmin() && $user->getKey() !== Auth::user()->getKey()) {
			$user->super_admin = $input->value('super_admin', false);
		}

		if ($input->offsetExists('active') && $user->getKey() !== Auth::user()->getKey()) {
			$user->active = $input->value('active', false);
		}

		if ($input->value('supervisor')) {
			if ($supervisor = User::query()->find($input->value('supervisor'))) {
				if ($supervisor->getKey() !== $user->getKey()) {
					$user->supervisor = $supervisor;
				}
			}
		}

		$user->save();
		return Response::success($user, __('User Updated'));
	}

	/**
	 * Delete a user
	 *
	 * @param User $user
	 *
	 * @return Response
	 */
	public function delete(User $user) : Response
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
	 * @param User $user
	 *
	 * @return Response
	 * @throws \Exception
	 */
	public function restore(User $user) : Response
	{
		UserRepository::repository()->restore($user);
		return Response::success();
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
	 * @param User $user
	 * @param $settings
	 *
	 * @return Response
	 */
	public function syncSettings(User $user, array $settings = [])
	{
		$user->settings = $settings;
		UserRepository::repository()->save($user);
		return Response::success();
	}



}