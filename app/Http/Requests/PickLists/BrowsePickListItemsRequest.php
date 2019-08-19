<?php

namespace Foundry\System\Http\Requests\PickLists;

use Doctrine\ORM\QueryBuilder;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\Core\Requests\Traits\IsBrowseRequest;
use Foundry\System\Http\Resources\PickListItem;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Services\PickListItemService;

class BrowsePickListItemsRequest extends PickListRequest implements ViewableFormRequestInterface, InputInterface
{

    use HasInput;
    use IsBrowseRequest;

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
		return !!($this->user());
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

		$paginator = PickListItemService::service()->browse($this->getEntity(), $inputs, $page, $limit );

		$result = $this->makeBrowseResource($paginator, $page, $limit);

		return Response::success($result);
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
