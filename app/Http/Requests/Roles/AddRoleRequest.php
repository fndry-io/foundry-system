<?php

namespace Foundry\System\Http\Requests\Roles;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\Role\RoleInput;
use Foundry\System\Services\RoleService;

class AddRoleRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.roles.add';
	}

	/**
	 * @return string
	 */
	public static function getInputClass(): string {
		return RoleInput::class;
	}

	/**
	 * Determine if the role is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return !!(auth_user());
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
			return RoleService::service()->create($this->input);
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

		$form->setTitle(__('Create Role'));
		$form->setButtons((new SubmitButtonType(__('Create'), $form->getAction())));
		$form->addChildren(
			RowType::withChildren($form->get('name'))
		);
		return $form;
	}
}
