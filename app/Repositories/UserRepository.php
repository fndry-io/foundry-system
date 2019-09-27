<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Repositories\ModelRepository;
use Foundry\System\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends ModelRepository {

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
	 * Find the user by their email address
	 *
	 * @param string $email
	 * @param int $perPage
	 *
	 * @return Paginator
	 */
	public function findByEmail(string $email, int $perPage = 20) : Paginator
	{
		return $this->filter(function(Builder $query) use ($email){
			$query->where('email', 'like', "%" . $email . "%");
		}, $perPage);
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
	public function getLabelList($name, $limit = 20, $deleted = false) {

		if ($deleted) {
			$qb = $this->getClassName()::withTrashed();
		} else {
			$qb = $this->query();
		}

		$qb->select('id', 'username', 'display_name');

		$qb->where(function(Builder $qb) use ($name) {
			$qb->orWhere('username', 'like', "%" . $name . "%");
			$qb->orWhere('display_name', 'like', "%" . $name . "%");
			$qb->orWhere('email', 'like', "%" . $name . "%");
		});

		$qb->limit($limit);

		return $qb->get();
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
	public function getEmailList($name, $limit = 20, $deleted = false) {

		if ($deleted) {
			$qb = $this->getClassName()::withTrashed();
		} else {
			$qb = $this->query();
		}

		$qb->select('email', 'display_name');

		$qb->where(function(Builder $qb) use ($name) {
			$qb->orWhere('username', 'like', "%" . $name . "%");
			$qb->orWhere('display_name', 'like', "%" . $name . "%");
			$qb->orWhere('email', 'like', "%" . $name . "%");
		});

		$qb->limit($limit);

		$list = $qb->get();

		return array_map(function($item){
			return "{$item['display_name']} <{$item['email']}>";
		}, $list);
	}

}