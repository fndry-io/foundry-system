<?php

namespace Foundry\System\Http\Requests\PickLists;

use Foundry\Core\Inputs\SimpleInputs;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\PickListItem\PickListItemInput;
use Foundry\System\Services\PickListItemService;

class AddPickListItemRequest extends PickListRequest implements InputInterface, ViewableFormRequestInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.pick-lists.items.add';
	}

	/**
	 * Make the input class for the request
	 *
	 * @param $inputs
	 *
	 * @return mixed
	 */
	public function makeInput($inputs)
	{
		return SimpleInputs::make($inputs, InputTypeCollection::make([
			(new TextInputType('label', __('Label'), true))
		]));
	}

	/**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	//todo update to use the permissions
        return ($this->user() && $this->user()->can('create pick list items')) && $this->getEntity()->is_tag;
    }

	/**
	 * Handle the request
	 *
	 * @see UserResource
	 * @return Response
	 */
    public function handle() : Response
    {
    	$input = new PickListItemInput([
    		'label' => $this->getInput()->value('label'),
		    'sequence' => 0,
		    'status' => true,
		    'picklist' => $this->getEntity()->getKey()
	    ]);
    	if ($input->validate()) {
    		$response = PickListItemService::service()->add($input);
    		if ($response->isSuccess()) {
    			$item = $response->getData();
    			return Response::success([
    				'label' => $item->label,
				    'id' => $item->getKey()
			    ]);
		    }
	    }
    }

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		$form->setValue('label', '');

		$form->setTitle(__('Add Tag'));
		$form->setButtons((new SubmitButtonType(__('Add'), $form->getAction())));

		$item = (new SectionType(__('Pick List Tag')))->addChildren(
			RowType::withChildren($form->get('label')->setValue(''))
		);

		$form->addChildren(
			$item
		);

		return $form;
	}

}
