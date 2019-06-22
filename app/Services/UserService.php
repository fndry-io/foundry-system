<?php

namespace Foundry\System\Services;

use Carbon\Carbon;
use Foundry\Core\Entities\Contracts\ApiTokenInterface;
use Foundry\Core\Entities\Contracts\EntityInterface;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\System\Entities\User;
use Foundry\System\Inputs\User\ForgotPasswordInput;
use Foundry\System\Inputs\User\ResetPasswordInput;
use Foundry\System\Inputs\User\UserEditInput;
use Foundry\System\Inputs\User\UserLoginInput;
use Foundry\System\Inputs\User\UserRegisterInput;
use Foundry\System\Repositories\UserRepository;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\TokenGuard;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserService {

	protected $repository;

	protected $per_page = 20;

	public function __construct(UserRepository $repository) {
		$this->repository = $repository;
	}

	/**
	 * @return User[]|LengthAwarePaginator
	 */
	public function all() : LengthAwarePaginator
	{
		$this->repository->paginateAll($this->per_page);
	}

	/**
	 * @param $id
	 *
	 * @return null|object|User
	 */
	public function find($id)
	{
		return $this->repository->find($id);
	}

	/**
	 * @param \Closure $builder
	 *
	 * @return User[]|LengthAwarePaginator
	 */
	public function filter(\Closure $builder = null) : LengthAwarePaginator
	{
		return $this->repository->filter($builder, $this->per_page);
	}

	/**
	 * @param UserLoginInput $input
	 *
	 * @return Response
	 */
	public function login(UserLoginInput $input) : Response
	{
		$inputs = $input->inputs();

		/**
		 * @var Guard $guard
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

		if($provider->retrieveByCredentials([
			'email' => $inputs['email'],
			'password' => $inputs['password']
		])){

			/**
			 * @var $user User
			 */
			$user = $this->repository->findOneBy([
				'email' => $inputs['email']
			]);

			//detect if the user is longer active
			if ($user->isActive()) {
				$guard->setUser($user);
				return $this->returnGuardUser($guard);
			} else {
				return Response::error(__("Account no longer active, please contact the Support Team"), 403);
			}
		} else {
			return Response::error(__("Permission denied, wrong password and username combination"), 401);
		}
	}

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
				$user->setApiToken(null);
				$this->repository->save($user);
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
	 * @param Guard $guard
	 *
	 * @return Response
	 */
	public function returnGuardUser(Guard $guard = null) : Response
	{
		/**
		 * @var $user User
		 */
		$user = $guard->user();
		$user->logged_in = true;
		$user->last_login_at = new Carbon();

		$data = [
			'user' => $user->only(['id', 'uuid', 'first_name', 'last_name', 'email'])
		];

		if ($guard instanceof TokenGuard && $user instanceof ApiTokenInterface) {
			$token = Str::random(60);

			//todo update to ensure the user token expires and the token guard loads it correctly
			$user->setApiToken($token);
			$user->setApiTokenExpiresAt(Carbon::now()->addDays(3));
			$data['token'] = hash('sha256', $token);
		}

		$this->repository->save($user);

		return Response::success($data);
	}

	/**
	 * @param UserRegisterInput|Inputs $input
	 *
	 * @return Response
	 */
	public function register(UserRegisterInput $input) : Response
	{
		$user = new User($input->inputs());
		$user->setActive(true);
		$user->setPassword($input->password);
		if ($input->super_admin === true) {
			$user->setSuperAdmin(true);
		}
		$this->repository->save($user);
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
			return Response::success();
		}
		else{
			$user = $this->repository->findOneBy(['email' => $input->email]);
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
			$user->setPassword($password);
			$user->setRememberToken(Str::random(60));
			$this->repository->save($user);
			event(new PasswordReset($user));
		} );

		if($response === Password::PASSWORD_RESET){
			return Response::success();
		} else {
			return Response::error(__("Account with provided E-mail address not found!"), 404);
		}
	}

	/**
	 * @param UserEditInput|Inputs $input
	 * @param User|EntityInterface $user
	 *
	 * @return Response
	 */
	public function edit(UserEditInput $input, User $user) : Response
	{
		$user->fill($input);
		if ($input->password) {
			$user->setPassword($input->password);
		}
		if ($input->super_admin === true) {
			$user->setSuperAdmin(true);
		} elseif ($input->super_admin) {
			$user->setSuperAdmin(false);
		}
		$this->repository->save($user);
		return Response::success($user);
	}

	/**
	 * Delete a user
	 *
	 * @param User|EntityInterface $user
	 *
	 * @return Response
	 */
	public function delete(User $user) : Response
	{
		$this->repository->delete($user);
		return Response::success($user);
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
	 * @return static
	 */
	static function service()
	{
		return app(static::class);
	}

}