<?php

namespace Foundry\System\Repositories;

use Doctrine\ORM\QueryBuilder;
use Foundry\Core\Repositories\EntityRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository extends EntityRepository {

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


}