<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\User\UserEditInput;
use Foundry\System\Services\UserService;
use Illuminate\Validation\Rule;

class EditUserRequest extends UserRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.auth.edit';
	}

	/**
	 * @param $inputs
	 *
	 * @return \Foundry\Core\Inputs\Inputs|UserEditInput
	 */
	public function makeInput($inputs) {
		return new UserEditInput($inputs);
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return !!($this->user());
	}

	public function rules() {
		$rules = $this->input->rules();
		if ($entity = $this->getEntity()) {
			$rules['email'] = [
				'required',
				Rule::unique('Foundry\System\Entities\User', 'email')->ignore($this->getEntity()->id)
			];
		}
		return $rules;
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
		return UserService::service()->edit($this->input, $this->getEntity());
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		$form->setTitle(__('Edit User'));
		$form->setButtons((new SubmitButtonType(__('Save'), $form->getAction())));
		$form->addChildren((new SectionType(__('Details')))->addChildren(
			RowType::withChildren($form->get('username'), $form->get('display_name')),
			RowType::withChildren($form->get('email'))
		));
		$form->addChildren(
			(new SectionType(__('Password'), __('If you need to change the password set the values below or leave them blank to leave the password as is.')))->addChildren(
				RowType::withChildren($form->get('password')->setValue(null), $form->get('password_confirmation')->setValue(null))
			)
		);
		return $form;
	}
}
