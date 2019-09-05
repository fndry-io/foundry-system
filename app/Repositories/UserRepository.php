<?php

namespace Foundry\System\Repositories;

use Doctrine\ORM\QueryBuilder;
use Foundry\Core\Repositories\EntityRepository;
use Foundry\System\Entities\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Facades\EntityManager;

class UserRepository extends EntityRepository {

	/**
	 * @return UserRepository|\Doctrine\Common\Persistence\ObjectRepository
	 */
	static function get() {
		return EntityManager::getRepository(User::class);
	}

	public function getAlias(): string {
		return 'u';
	}

	public function findByEmail(string $email, int $perPage = 20) : LengthAwarePaginator
	{
		return $this->filter(function(QueryBuilder $query) use ($email){
			$query->where('u.email LIKE :email')
			      ->setParameter('email', "$email");
		}, $perPage);
	}

	public function getLabelList($name, $limit = 20, $deleted = false) {

		$qb = $this->query();
		$qb->select('u.id', 'u.username, u.display_name');

		$where = $qb->expr()->andX();

		if (!$deleted) {
			$where->add($qb->expr()->isNull('u.deleted_at'));
		}

		$where->add($qb->expr()->orX(
			$qb->expr()->like('u.username', ':name'),
			$qb->expr()->like('u.display_name', ':name'),
			$qb->expr()->like('u.email', ':name')
		));
		$qb->setParameter('name', "%$name%");

		if ($where->count() > 0) {
			$qb->where($where);
		}

		$qb->setMaxResults($limit);

		return $qb->getQuery()->getArrayResult();
	}

	public function getEmailList($name, $limit = 20, $deleted = false) {

		$qb = $this->query();
		$qb->select('u.display_name', 'u.email');

		$where = $qb->expr()->andX();

		if (!$deleted) {
			$where->add($qb->expr()->isNull('u.deleted_at'));
		}

		$where->add($qb->expr()->orX(
			$qb->expr()->like('u.username', ':name'),
			$qb->expr()->like('u.display_name', ':name'),
			$qb->expr()->like('u.email', ':name')
		));
		$qb->setParameter('name', "%$name%");

		if ($where->count() > 0) {
			$qb->where($where);
		}

		$qb->setMaxResults($limit);

		$list = $qb->getQuery()->getArrayResult();
		return array_map(function($item){
			return "{$item['display_name']} <{$item['email']}>";
		}, $list);
	}


}