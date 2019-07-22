<?php

namespace Foundry\System\Http\Requests\Users;

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
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EditUserRequest extends UserRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.users.edit';
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
			$rules['username'] = [
				'required',
				Rule::unique('Foundry\System\Entities\User', 'username')->ignore($this->getEntity()->id)
			];
			$rules['display_name'] = [
				'required',
				Rule::unique('Foundry\System\Entities\User', 'display_name')->ignore($this->getEntity()->id)
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
		$form->addChildren(
			(new SectionType(__('Details')))->addChildren(
				RowType::withChildren($form->get('username')->setAutocomplete(false), $form->get('display_name')->setAutocomplete(false)),
				RowType::withChildren($form->get('email')->setAutocomplete(false))
			)
		);
		if (Auth::user()->id === $this->entity->getId() || Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
			$form->addChildren(
				(new SectionType(__('Password'), __('If you need to change the password set the values below or leave them blank to leave the password as is.')))->addChildren(
					RowType::withChildren($form->get('password')->setValue(null)->setAutocomplete(false), $form->get('password_confirmation')->setValue(null)->setAutocomplete(false))
				)
			);
		}
		if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
			if (!$this->entity->isSuperAdmin()) {
				$children = [];
				$children[] = RowType::withChildren($form->get('active'));
				$form->addChildren(
					(new SectionType(__('Access'), __('Controls the access this user has to the system.')))->addChildren(...$children)
				);
			}
		}
		return $form;
	}
}
