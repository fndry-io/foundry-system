<?php

namespace Foundry\System\Http\Requests\Addresses;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\Address\AddressInput;
use Foundry\System\Services\AddressService;

class EditAddressRequest extends AddressRequest implements ViewableFormRequestInterface, EntityRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.addresses.edit';
	}

	/**
	 * @param $inputs
	 *
	 * @return string
	 */
	public function makeInput($inputs) {
		return new AddressInput($inputs);
	}

	/**
	 * Determine if the Address is authorized to make this request.
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
		return AddressService::service()->edit($this->input, $this->getEntity());
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		$form->setTitle(__('Update Address'));
		$form->setButtons((new SubmitButtonType(__('Update'), $form->getAction())));
		$form->addChildren(
			RowType::withChildren($form->get('type')),
			RowType::withChildren($form->get('street')),
			RowType::withChildren($form->get('city'), $form->get('region')),
			RowType::withChildren($form->get('country'), $form->get('code'))
		);
		return $form;
	}

}
