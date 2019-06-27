<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\User\UserInput;
use Foundry\System\Inputs\User\UserRegisterInput;
use Foundry\System\Services\UserService;

class AddUserRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.users.add';
	}

	/**
	 * @param $inputs
	 *
	 * @return \Foundry\Core\Inputs\Inputs|UserInput
	 */
	public function makeInput($inputs) {
		return new UserInput($inputs);
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

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
		return UserService::service()->add($this->input);
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
			RowType::withChildren($form->get('email')),
			RowType::withChildren($form->get('password'), $form->get('password_confirmation'))
		);
		return $form;
	}
}
