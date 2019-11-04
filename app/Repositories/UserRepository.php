<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Entities\Contracts\IsUser;
use Foundry\Core\Models\Model;
use Foundry\Core\Repositories\ModelRepository;
use Foundry\Core\Repositories\Traits\SoftDeleteable;
use Foundry\System\Events\UserRegistered;
use Foundry\System\Models\File;
use Foundry\System\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

/**
 * Class CompanyRepository
 *
 * @method IsUser|User|boolean save(IsUser | Model | int $model)
 * @method boolean delete(IsUser | Model | int $model)
 * @method User|Model getModel(int|Model $id)
 *
 * @package Modules\Agm\Contacts\Repositories
 */
class UserRepository extends ModelRepository
{
    use SoftDeleteable;

	/**
	 * Returns the class name of the object managed by the repository.
	 *
	 * @return string|User
	 */
	public function getClassName()
	{
		return User::class;
	}

	/**
	 * @param array $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Paginator
	 */
	public function browse(array $inputs, $page = 1, $perPage = 20, $sortBy = null, $sortDesc = false): Paginator
	{
		return $this->filter(function (Builder $query) use ($inputs,$sortBy,$sortDesc) {

			$query
				->select('users.*');

			if ($search = Arr::get($inputs, 'search', null)) {
				$query->where(function (Builder $query) use ($search) {
					$query->where('username', 'like', "%" . $search . "%");
					$query->where('display_name', 'like', "%" . $search . "%");
					$query->where('email', 'like', "%" . $search . "%");
				});
			}

			$deleted = Arr::get($inputs, 'deleted', 'undeleted');
			if ($deleted == 'deleted') {
				$query->onlyTrashed();
			}

            $sortDesc = ($sortDesc === true) ? 'DESC' : 'ASC';
            if ($sortBy === 'username') {
                $query->orderBy('users.username', $sortDesc);
            } else if($sortBy ==='job_title') {
                $query->orderBy('users.job_title', $sortDesc);
            } else {
                $query->orderBy('users.display_name', $sortDesc);
            }

			return $query;

		}, $page, $perPage);
	}

	/**
	 * Find the user by their email address
	 *
	 * @param string $email
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Paginator
	 */
	public function findByEmail(string $email, $page = 1, $perPage = 20): Paginator
	{
		return $this->filter(function (Builder $query) use ($email) {
			$query->select('*')->where('email', 'like', "%" . $email . "%");
			return $query;
		}, $page, $perPage);
	}

	/**
	 * Get a list of users
	 *
	 * @param $name
	 * @param int $limit
	 * @param bool $deleted
	 *
	 * @return User[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|\Illuminate\Support\Collection
	 */
	public function getLabelList($name, $limit = 20, $deleted = false)
	{

		if ($deleted) {
			$query = $this->getClassName()::withTrashed();
		} else {
			$query = $this->query();
		}

		$query->select('id', 'username', 'display_name');

		$query->where(function (Builder $query) use ($name) {
			$query->orWhere('username', 'like', "%" . $name . "%");
			$query->orWhere('display_name', 'like', "%" . $name . "%");
			$query->orWhere('email', 'like', "%" . $name . "%");
		});

		$query->limit($limit);

		return $query->get();
	}

	/**
	 * Get a list of user email addresses
	 *
	 * @param $name
	 * @param int $limit
	 * @param bool $deleted
	 *
	 * @return array
	 */
	public function getEmailList($name, $limit = 20, $deleted = false)
	{

		if ($deleted) {
			$query = $this->getClassName()::withTrashed();
		} else {
			$query = $this->query();
		}

		$query->select('email', 'display_name');

		$query->where(function (Builder $query) use ($name) {
			$query->orWhere('username', 'like', "%" . $name . "%");
			$query->orWhere('display_name', 'like', "%" . $name . "%");
			$query->orWhere('email', 'like', "%" . $name . "%");
		});

		$query->limit($limit);

		$list = $query->get();

		return array_map(function ($item) {
			return "{$item['display_name']} <{$item['email']}>";
		}, $list->toArray());
	}

	/**
	 * @param $data
	 *
	 * @return bool|\Foundry\Core\Models\Model|IsUser
	 */
	public function register($data)
	{
		$user           = UserRepository::make($data);
		$user->active   = true;
		$user->password = $data['password'];
		if ($user->save()) {
		    event(new UserRegistered($user));
			return $user;
		} else {
			return false;
		}
	}

	/**
	 * @param $id
	 * @param $password
	 *
	 * @return bool|Model|IsUser
	 */
	public function resetPassword($id, $password)
	{
		$user = $this->getModel($id);

		$user->password = $password;

		if ($user->save()) {
			event(new PasswordReset($user));

			return $user;
		} else {
			return false;
		}
	}

	/**
	 * @param array $data
	 *
	 * @return bool|Model|IsUser
	 */
	public function insert($data)
	{
		$user = self::make($data);

		if ($password = Arr::get($data, 'password')) {
			$user->password = $password;
		}

		if (Auth::user() && Auth::user()->isSuperAdmin()) {

			//todo change to control this in the form
			$user->active = true;

			if (Arr::get($data, 'super_admin', false) === true) {
				$user->super_admin = true;
			} else {
				$user->super_admin = false;
			}
		}

		if ($supervisor = Arr::get($data, 'supervisor', null)) {
			if ($supervisor = $this->find($supervisor)) {
				$user->supervisor = $supervisor;
			}
		}

        if ((Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()) && Arr::exists($data, 'roles')) {
            $roleIds = Arr::get($data, 'roles');
            $user->syncRoles($roleIds);
        }

		if ($this->save($user)) {
			return $user;
		} else {
			return false;
		}
	}

	/**
	 * @param IsUser|User|int $id
	 * @param array $data
	 *
	 * @return bool|Model|IsUser
	 */
	public function update($id, $data)
	{
		$user = $this->getModel($id);

		$user->fill($data);

		if ($password = Arr::get($data, 'password')) {
			$user->password = $password;
		}

		if ($profile_image = Arr::get($data, 'profile_image')) {
		    if ($file = File::query()->find($profile_image)) {
                $user->profile_image()->associate($file);
            }
        }

		if (Auth::user()->isSuperAdmin() && $user->getKey() !== Auth::user()->getKey()) {
			$user->super_admin = Arr::get($data, 'super_admin', false);
		}

        if ((Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()) && Arr::exists($data, 'roles')) {
            $roleIds = Arr::get($data, 'roles');
            $user->syncRoles($roleIds);
        }

        if (Arr::exists($data, 'active') && $user->getKey() !== Auth::user()->getKey()) {
			$user->active = Arr::get($data, 'active', false);
		}

		if ($supervisor = Arr::get($data, 'supervisor')) {
			if ($supervisor = $this->find($supervisor)) {
				if ($supervisor->getKey() !== $user->getKey()) {
					$user->supervisor = $supervisor;
				}
			}
		}

		if ($user->save()) {
			return $user;
		} else {
			return false;
		}
	}

	/**
	 * @param $id
	 * @param array $settings
	 *
	 * @return bool|IsUser|User
	 */
	public function syncSettings($id, array $settings)
	{
		$user = $this->getModel($id);
		$user->settings = $settings;

		if ($user->save()) {
			return $user;
		} else {
			return false;
		}
	}

}
