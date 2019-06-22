<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\Core\Entities\Contracts\EntityInterface;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\TagType;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Entities\Entity;
use Foundry\System\Entities\User;
use Foundry\System\Inputs\User\ForgotPasswordInput;
use Foundry\System\Services\UserService;

class ForgotPasswordRequest extends FormRequest implements ViewableFormRequestInterface
{

	/**
	 * @var ForgotPasswordInput
	 */
	protected $input;

	public static function name(): String {
		return 'foundry.system.auth.forgot_password';
	}

	/**
	 * @return string
	 */
	public static function getInputClass(): string {
		return ForgotPasswordInput::class;
	}

	/**
	 * @param mixed $id
	 *
	 * @return EntityInterface|Entity|null|object|User
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
	 */
	public function handle() : Response
	{
		$response = $this->input->validate();
		if ($response->isSuccess()) {
			return UserService::service()->forgotPassword($this->input);
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

		$form->setTitle(__('Forgot your Password?'));
		$form->addChildren((new TagType('p', __("Don't worry. Resetting your password is easy. Just tell us the email address you used to register with."))));
		$form->setButtons((new SubmitButtonType(__('Send'), $form->getAction())));
		$form->addChildren(
			RowType::withChildren($form->get('email'))
		);
		return $form;
	}
}
