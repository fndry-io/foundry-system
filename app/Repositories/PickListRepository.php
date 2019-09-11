<?php

namespace Foundry\System\Repositories;

use Doctrine\ORM\QueryBuilder;
use Foundry\Core\Repositories\EntityRepository;
use Foundry\System\Entities\PickList;
use Foundry\System\Entities\PickListItem;
use Illuminate\Support\Facades\Cache;
use LaravelDoctrine\ORM\Facades\EntityManager;


class PickListRepository  extends EntityRepository {

	public function getAlias(): string {
		return 'picklist';
	}

	/**
	 * @return \Doctrine\Common\Persistence\ObjectRepository|PickListRepository
	 */
	static function get()
	{
		return EntityManager::getRepository(PickList::class);
	}

    public function getLabelList() {

        $qb = $this->query();
        $qb->select('picklist.id', 'picklist.label');
        return $qb->getQuery()->getArrayResult();
    }

	/**
	 * @param string $identifier
	 *
	 * @return mixed
	 */
    public function getCachedSelectableList(string $identifier)
    {
    	return Cache::rememberForever('picklist::' . $identifier, function() use ($identifier){
		    if ($picklist = $this->getSelectableList($identifier)) {
			    return $picklist;
		    } else {
			    throw new \Exception(sprintf('Pick List %s does not exist', $identifier));
		    }
	    });
    }

	/**
	 * @param $identifier
	 *
	 * @return null|object
	 */
    public function getSelectableList($identifier)
    {
	    if (!$picklist = $this->findOneBy(['identifier' => $identifier])) {
	    	return null;
	    }

	    /**
	     * @var QueryBuilder $qb
	     */
	    $qb = EntityManager::getRepository(PickListItem::class)->query();
	    $qb->select('picklist_item.id', 'picklist_item.identifier', 'picklist_item.label');
	    $qb->orderBy('picklist_item.sequence', 'ASC');
	    $qb->orderBy('picklist_item.label', 'ASC');

	    $where = $qb->expr()->andX();

	    $where->add($qb->expr()->eq('picklist_item.picklist', ':picklist'));
	    $qb->setParameter('picklist', $picklist);

	    $where->add($qb->expr()->eq('picklist_item.status', ':status'));
	    $qb->setParameter('status', true);

	    $qb->where($where);

	    $picklist = $picklist->toArray();
	    $picklist['items'] = $qb->getQuery()->getArrayResult();

	    return $picklist;
    }

	/**
	 * @param string $identifier
	 */
    public function clearCachedSelectableList(string $identifier)
    {
	    Cache::forget('picklist::' . $identifier);
    }

}