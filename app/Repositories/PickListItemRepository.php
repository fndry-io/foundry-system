<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Entities\Contracts\IsPickList;
use Foundry\Core\Entities\Contracts\IsPickListItem;
use Foundry\Core\Models\Model;
use Foundry\Core\Repositories\ModelRepository;
use Foundry\System\Models\PickListItem;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * Class CompanyRepository
 *
 * @method boolean delete(IsPickListItem | Model | int $model)
 * @method IsPickListItem|Model getModel(Model $id)
 *
 * @package Modules\Agm\Contacts\Repositories
 */
class PickListItemRepository extends ModelRepository
{

	/**
	 * Returns the class name of the object managed by the repository.
	 *
	 * @return string|PickListItem
	 */
	public function getClassName()
	{
		return PickListItem::class;
	}

	/**
	 * @param IsPickList $pick_list
	 * @param array $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return \Illuminate\Contracts\Pagination\Paginator
	 */
	public function browse(IsPickList $pick_list, array $inputs, $page = 1, $perPage = 20, $sortBy = null, $sortDesc = false): Paginator
	{
		return $this->filter(function (Builder $query) use ($pick_list, $inputs,$sortBy,$sortDesc) {

			$query
				->select(
					'picklist_items.id',
					'picklist_items.label',
					'picklist_items.identifier',
					'picklist_items.description',
					'picklist_items.sequence',
					'picklist_items.status'
				)
                ->selectRaw('IF(picklists.default_item = picklist_items.id, true, false) as is_default')
				->join('picklists', 'picklists.id', '=', 'picklist_items.picklist_id');

			if ($search = Arr::get($inputs, 'search')) {
				$query->where('picklist_items.label', 'like', "%" . $search . "%");
			}

			$query->where('picklist_items.picklist_id', $pick_list->getKey());

            $sortDesc = ($sortDesc === true) ? 'DESC' : 'ASC';
            if ($sortBy === 'label') {
                $query->orderBy('picklist_items.label', $sortDesc);
            } else {
                $query->orderBy('picklist_items.label', $sortDesc);
            }

			return $query;

		}, $page, $perPage);
	}

	/**
	 * @param IsPickList $pick_list
	 * @param null $name
	 *
	 * @return Builder[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function getLabelList(IsPickList $pick_list, $name = null)
	{

		$query = $this->query();
		$query->select('id', 'label');
		$query->where('picklist_id', $pick_list->getKey());

		if ($name) {
			$query->where('label', 'like', "%$name%");
		}

		return $query->get();
	}

	/**
	 * @param array $data
	 *
	 * @return bool|IsPickListItem|Model|mixed
	 */
	public function insert($data)
	{
		$pickListItem = self::make($data);
		if ($id = Arr::get($data, 'picklist')) {
			$pickListItem->picklist = $id;
		}

		$result = $pickListItem->save();

		if (Arr::get($data, 'default_item') && $pickListItem->picklist) {
			$pickListItem->picklist->default_item = $pickListItem->getKey();
			$pickListItem->picklist->save();
		}

		PickListRepository::repository()->clearCachedSelectableList($pickListItem->picklist->identifier);

		if ($result) {
			return $pickListItem;
		} else {
			return false;
		}
	}

    public function read($picklistItem)
    {
        $picklistItem = $this->getModel($picklistItem);

        return $picklistItem;
    }

	/**
	 * @param IsPickListItem|Model|int $id
	 * @param array $data
	 *
	 * @return IsPickListItem|Model|boolean
	 */
	public function update($id, $data)
	{
		$pickListItem = $this->getModel($id);

		$default_item = $pickListItem->picklist->default_item;

		$pickListItem->fill($data);

		$result = $pickListItem->save();

		if ($pickListItem->picklist) {
			$should = Arr::get($data, 'default_item');
			if ( ! $should && ($default_item === $pickListItem->getKey())) {
				$pickListItem->picklist->default_item = null;
			} elseif ($should) {
				if ($pickListItem->status == true) {
					$pickListItem->picklist->default_item = $pickListItem->getKey();
				}
			}
			$pickListItem->picklist->save();
		}

		PickListRepository::repository()->clearCachedSelectableList($pickListItem->picklist->identifier);

		$pickListItem->makeVisible('picklist');

		if ($result) {
			return $pickListItem;
		} else {
			return false;
		}
	}


}
