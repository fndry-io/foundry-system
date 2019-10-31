<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Entities\Contracts\IsPickList;
use Foundry\Core\Models\Model;
use Foundry\Core\Repositories\ModelRepository;
use Foundry\System\Models\PickList;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
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

	/**
	 * @param array $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Paginator
	 */
	public function browse(array $inputs, $page = 1, $perPage = 20,$sortBy = 'picklists.label', $sortDesc = false): Paginator
	{
		return $this->filter(function (Builder $query) use ($inputs,$sortBy,$sortDesc) {
			$query
				->select('picklists.*');

            if ($sortBy) {
                $sortDesc = ($sortDesc === true) ? 'DESC' : 'ASC';
                if ($sortBy === 'label') {
                    $query->orderBy('picklists.label', $sortDesc);
                }
            }
            else{
                $query->orderBy('picklists.label', 'ASC');
            }
			return $query;
		}, $page, $perPage);
	}

	/**
	 * @param array $data
	 *
	 * @return bool|Model|IsPickList
	 */
	public function insert($data)
	{
		$pickList = self::make($data);
		$result = $pickList->save();
		if ($result) {
			return $pickList;
		} else {
			return false;
		}
	}

	/**
	 * @param IsPickList|Model|int $id
	 * @param array $data
	 *
	 * @return IsPickList|Model|boolean
     * @throws \Exception
     */
	public function update($id, $data)
	{
		$pickList = $this->getModel($id);
		$pickList->fill($data);

		$result = $pickList->save();

		$this->clearCachedSelectableList($pickList->identifier);

		if ($result) {
			return $pickList;
		} else {
			return false;
		}
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
	 */
    public function getLabelList() {

        $query = $this->query();
        $query->select('id', 'label');
        return $query->get();
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
	     * @var Builder $query
	     */
	    $query = $picklist->items();
	    $query->select('id', 'identifier', 'label');
	    $query->orderBy('sequence', 'ASC');
	    $query->orderBy('label', 'ASC');

	    $query->where('status', true);

	    $picklist = $picklist->toArray();
	    $picklist['items'] = $query->get()->toArray();

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
