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

}