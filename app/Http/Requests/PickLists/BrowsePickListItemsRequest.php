<?php

namespace Foundry\System\Http\Requests\PickLists;

use Doctrine\ORM\QueryBuilder;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\BrowseableRequest;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Http\Resources\PickList;
use Foundry\System\Http\Resources\PickListItem;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Services\PickListItemService;
use Foundry\System\Services\PickListService;

class BrowsePickListItemsRequest extends PickListRequest implements ViewableFormRequestInterface, InputInterface
{

    use HasInput;
    use BrowseableRequest;

	public static function name(): String {
		return 'foundry.system.pick-lists.items.browse';
	}

    public function makeInput($inputs) {
        return new SearchFilterInput($inputs);
    }

	/**
	 * Determine if the item is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('browse pick list items'));
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
        $inputs = $this->getInput();

		$page = $this->input('page', 1);
		$limit = $this->input('limit', 20);
        list($page, $limit, $sortBy, $sortDesc) = $this->getBrowseMeta(1, 20, 'picklist_items.label', false);
        return PickListItemService::service()->browse($this->getEntity(), $inputs, $page, $limit, $sortBy, $sortDesc )->asResource(PickListItem::class, true);
	}

    public function view() : FormType
    {
        $form = $this->form();

        $form->setTitle(__('Update Pick List Item'));
        $form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
        $form->addChildren(
            RowType::withChildren($form->get('search'))
        );
        return $form;
    }

}
