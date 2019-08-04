<?php

namespace Foundry\System\Repositories;

use Doctrine\ORM\QueryBuilder;
use Foundry\Core\Repositories\EntityRepository;
use Foundry\System\Entities\PickListItem;
use Illuminate\Support\Facades\Cache;
use LaravelDoctrine\ORM\Facades\EntityManager;


class PickListRepository  extends EntityRepository {

	public function getAlias(): string {
		return 'picklist';
	}


    public function getLabelList() {

        $qb = $this->query();
        $qb->select('picklist.id', 'picklist.name');
        return $qb->getQuery()->getArrayResult();
    }

	/**
	 * @param string $slug
	 *
	 * @return mixed
	 */
    public function getCachedSelectableList(string $slug)
    {
    	return Cache::rememberForever('picklist::' . $slug, function() use ($slug){
		    if ($picklist = $this->findOneBy(['slug' => $slug])) {
			    /**
			     * @var QueryBuilder $qb
			     */
			    $qb = EntityManager::getRepository(PickListItem::class)->query();
			    $qb->select('picklist_item.id', 'picklist_item.slug', 'picklist_item.name');
			    $qb->orderBy('picklist_item.sequence', 'ASC');
			    $qb->orderBy('picklist_item.name', 'ASC');

			    $where = $qb->expr()->andX();

			    $where->add($qb->expr()->eq('picklist_item.picklist', ':picklist'));
			    $qb->setParameter('picklist', $picklist);

			    $where->add($qb->expr()->eq('picklist_item.status', ':status'));
			    $qb->setParameter('status', true);

			    $qb->where($where);

			    $picklist = $picklist->toArray();
			    $picklist['items'] = $qb->getQuery()->getArrayResult();

			    return $picklist;

		    } else {
			    throw new \Exception(sprintf('Pick List %s does not exist', $slug));
		    }
	    });
    }

	/**
	 * @param string $slug
	 */
    public function clearCachedSelectableList(string $slug)
    {
	    Cache::forget('picklist::' . $slug);
    }

}