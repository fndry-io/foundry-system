<?php

namespace Foundry\System\Http\Requests\PickLists;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\System\Repositories\PickListItemRepository;

class ListPickListItemsRequest extends PickListRequest implements ViewableFormRequestInterface
{

	public static function name(): String {
		return 'foundry.system.pick-lists.items.list';
	}

	/**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	//todo update to use the permissions
        return !!($this->user());
    }

	/**
	 * Handle the request
	 *
	 * @see UserResource
	 * @return Response
	 */
    public function handle() : Response
    {
    	$repo = PickListItemRepository::get();
	    $q = $this->input('q', '');
    	if (strlen($q) < 3) {
    		return Response::error(__('Search query must be greater than 3 characters'), 422);
	    }
    	$results = $repo->getLabelList($this->getEntity(), $q);
        return Response::success($results);
    }

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
    public function view() : FormType
    {
    	$form = $this->form();

	    $form->setTitle(__('Select Users'));
    	$form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
    	$form->addChildren(
    		RowType::withChildren((new TextInputType('q', 'Query', true)))
	    );
    	return $form;
    }


}
