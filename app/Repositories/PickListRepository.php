<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Repositories\ModelRepository;
use Foundry\System\Models\PickList;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;

class PickListRepository extends ModelRepository {

	/**
	 * Returns the class name of the object managed by the repository.
	 *
	 * @return string
	 */
	public function getClassName()
	{
		return PickList::class;
	}

    public function getLabelList() {

        $qb = $this->query();
        $qb->select('id', 'label');
        return $qb->get();
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
	     * @var Builder $qb
	     */
	    $qb = PickListItemRepository::repository()->query();
	    $qb->select('id', 'identifier', 'label');
	    $qb->orderBy('sequence', 'ASC');
	    $qb->orderBy('label', 'ASC');

	    $qb->where('picklist_id', $picklist);
	    $qb->where('status', true);

	    $picklist = $picklist->toArray();
	    $picklist['items'] = $qb->get()->toArray();

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