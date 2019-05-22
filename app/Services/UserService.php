<?php

namespace Foundry\System\Services;

use Carbon\Carbon;
use Foundry\Core\Requests\Response;
use Foundry\System\Entities\User;
use Foundry\System\Inputs\User\UserLoginInput;
use Foundry\System\Inputs\User\UserRegisterInput;
use Foundry\System\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

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
	 * @return static
	 */
	static function service()
	{
		return app(static::class);
	}

}