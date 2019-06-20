<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\Core\Entities\Contracts\EntityInterface;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\System\Entities\Entity;
use Foundry\System\Entities\User;
use Foundry\System\Inputs\User\UserRegisterInput;
use Foundry\System\Services\UserService;
use LaravelDoctrine\ORM\Facades\EntityManager;

class RegisterUserRequest extends FormRequest implements ViewableFormRequestInterface
{

	/**
	 * @var UserRegisterInput
	 */
	protected $input;

	public static function name(): String {
		return 'foundry.system.users.register';
	}

	/**
	 * @return string
	 */
	public static function getInputClass(): string {
		return UserRegisterInput::class;
	}

	/**
	 * @param mixed $id
	 *
	 * @return EntityInterface|Entity|null|object|User
	 */
	public function getEntity($id)
	{
		return EntityManager::getRepository(User::class)->find($id);
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
			return UserService::service()->register($this->input);
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

		$form->setTitle(__('Create And Account'));
		$form->setButtons((new SubmitButtonType(__('Register'), $form->getAction())));
		$form->addChildren(
			RowType::withChildren($form->get('first_name'), $form->get('last_name')),
			RowType::withChildren($form->get('email')),
			RowType::withChildren($form->get('password')),
			RowType::withChildren($form->get('password_confirmation'))
		);
		return $form;
	}
}
