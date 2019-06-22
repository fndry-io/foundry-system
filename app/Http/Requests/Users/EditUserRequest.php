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

class EditUserRequest extends UserRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.users.edit';
	}

	/**
	 * @return string
	 */
	public static function getInputClass(): string {
		return UserEditInput::class;
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
		return (auth_user());
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
		$response = $this->input->validate();
		if ($response->isSuccess()) {
			return UserService::service()->edit($this->input, $this->getEntity());
		}
		return $response;
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		$form->setTitle(__('Create User'));
		$form->setButtons((new SubmitButtonType(__('Save'), $form->getAction())));
		$form->addChildren(
			RowType::withChildren($form->get('first_name'), $form->get('last_name')),
			RowType::withChildren($form->get('email'))
		);
		//if (auth_user()->id === $this->entity->getId() || (auth_user()->isAdmin() && !auth_user()->isSuperAdmin())) {
			$form->addChildren(
				(new SectionType(__('Password'), __('If you need to change the password for this user set the values below or leave them blank to leave the password as is.')))->addChildren(
					RowType::withChildren($form->get('password')->setValue(null), $form->get('password_confirmation')->setValue(null))
				)
			);
		//}
		return $form;
	}
}
