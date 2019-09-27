<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\User\UserRegisterInput;
use Foundry\System\Services\UserService;
use Illuminate\Validation\Rule;

class RegisterUserRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): string {
		return 'foundry.system.users.register';
	}

	/**
	 * @param $inputs
	 *
	 * @return \Foundry\Core\Inputs\Inputs|UserRegisterInput
	 */
	public function makeInput($inputs) {
		return new UserRegisterInput($inputs);
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	public function rules() {
		$rules = $this->input->rules();
		$rules['email'] = [
			'required',
			'email',
			Rule::unique('users', 'email')
		];
		$rules['username'] = [
			'required',
			'username',
			Rule::unique('users', 'username')
		];
		$rules['display_name'] = [
			'required',
			Rule::unique('users', 'display_name')
		];
		return $rules;
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
		return UserService::service()->register($this->input);
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		$form->setTitle(__('Create And Account'));
		$form->setButtons((new SubmitButtonType(__('Register'), $form->getAction())));
		$form->addChildren(
			(new SectionType(__('Details')))->addChildren(
				RowType::withChildren($form->get('username'), $form->get('display_name')),
				RowType::withChildren($form->get('email'))
			)
		);
		$form->addChildren(
			(new SectionType(__('Password')))->addChildren(
				RowType::withChildren($form->get('password'), $form->get('password_confirmation'))
			)
		);
		return $form;
	}
}
