<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\Types\Token;
use Foundry\System\Inputs\User\ResetPasswordInput;
use Foundry\System\Inputs\User\Types\Email;
use Foundry\System\Inputs\User\Types\Password;
use Foundry\System\Inputs\User\Types\PasswordConfirmation;
use Foundry\System\Models\User;
use Foundry\System\Services\UserService;

class ResetPasswordRequest extends FormRequest implements InputInterface, ViewableFormRequestInterface
{
	use HasInput;

	/**
	 * @var ResetPasswordInput
	 */
	protected $input;

	public static function name(): String {
		return 'foundry.system.auth.reset_password';
	}

	/**
	 * @param $inputs
	 *
	 * @return \Foundry\Core\Inputs\Inputs|ResetPasswordInput
	 */
	public function makeInput($inputs) {
		return new ResetPasswordInput($inputs);
	}

	/**
	 * @param mixed $id
	 *
	 * @return null|object|User
	 */
	public function getEntity($id)
	{
		return null;
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

	/**
	 * Handle the request
	 *
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
	public function handle() : Response
	{
		if ($this->input->validate()) {
			return UserService::service()->resetPassword($this->input);
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

		$form->setTitle(__('Reset Password'));
		$form->setButtons((new SubmitButtonType(__('Reset Password'), $form->getAction())));
		$form->addChildren(
			Token::input(),
			Email::input(),
			Password::input()->addRule('min:8')->addRule('max:20')->addRule('confirmed:password_confirmation'),
			PasswordConfirmation::input()
		);
		return $form;
	}
}
