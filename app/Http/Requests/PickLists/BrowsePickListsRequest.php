<?php

namespace Foundry\System\Http\Requests\PickLists;

use Doctrine\ORM\QueryBuilder;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\BrowseableRequest;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Http\Resources\PickList;
use Foundry\System\Http\Resources\User;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Services\PickListService;
use Foundry\System\Services\UserService;

class BrowsePickListsRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
    use HasInput;
    use BrowseableRequest;

	public static function name(): String {
		return 'foundry.system.pick-lists.browse';
	}

	/**
	 * @param $inputs
	 *
	 * @return \Foundry\Core\Inputs\Inputs|SearchFilterInput
	 */
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
        return ($this->user() && $this->user()->can('browse pick lists'));
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

        list($page, $limit, $sortBy, $sortDesc) = $this->getBrowseMeta(1, 20, 'system_picklists.label', false);
		return PickListService::service()->browse($inputs, $page, $limit, $sortBy, $sortDesc)->asResource(PickList::class, true);
	}

	/**
	 * @return FormType
	 */
    public function view() : FormType
    {
        $form = $this->form();

        $form->setTitle(__('Pick Lists'));
        $form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
        $form->addChildren(
            RowType::withChildren($form->get('search'))
        );
        return $form;
    }

}
