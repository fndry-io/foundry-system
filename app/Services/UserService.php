<?php

namespace Foundry\System\Services;

use Carbon\Carbon;
use Foundry\Core\Requests\Response;
use Foundry\System\Entities\User;
use Foundry\System\Inputs\User\ForgotPasswordInput;
use Foundry\System\Inputs\User\ResetPasswordInput;
use Foundry\System\Inputs\User\UserLoginInput;
use Foundry\System\Inputs\User\UserRegisterInput;
use Foundry\System\Repositories\UserRepository;
use Illuminate\Auth\Events\PasswordReset;
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

	public function login(UserLoginInput $input) : Response
	{
		//if current logged in user, log them out first
		if (auth_user()) {
			Auth::logout();
		}

		$inputs = $input->inputs();

		$user = $this->repository->findBy([
			'email' => $inputs['email']
		]);

		if($user) {
//			//determine the guard for this user and load that guard for authentication
//			switch ($user->guard_name) {
//				case 'store':
//					Auth::shouldUse('store');
//					break;
//				default:
//					break;
//			}

			if(Auth::attempt([
				'email' => $inputs['email'],
				'password' => $inputs['password']
			])){
				/**
				 * @var $user User
				 */
				$user = Auth::user();

				//detect if the user is no longer active
				if (!$user->isActive()) {
					Auth::logout();
					return Response::error(__("Account no longer active, please contact the Okinus Support Team"), 403);
				} else {
					return $this->returnSessionUser($user);
				}

			}
		}
		return Response::error(__("Permission denied, wrong password and username combination"), 401);
	}

	public function logout()
	{
		//if current logged in user, log them out first
		if (auth_user()) {
			Auth::logout();
		}
		Session::invalidate();
		return Response::success();
	}

	/**
	 * @param $user
	 *
	 * @return Response
	 */
	public function returnSessionUser(User $user) : Response
	{
		$user->logged_in = true;
		$user->last_login_at = new Carbon();
		$this->repository->save($user);

		return Response::success([
			'user' => $user->only(['id', 'uuid', 'first_name', 'last_name', 'email']),
			//'token' => $user->createToken('app')->accessToken
		]);
	}

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