<?php

namespace Foundry\System\Http\Requests\Roles;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
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
	 * @param $inputs
	 *
	 * @return RoleInput
	 */
	public function makeInput($inputs) {
		return new RoleInput($inputs);
	}

	/**
	 * Determine if the role is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('create roles'));
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
		return RoleService::service()->add($this->input);
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
            (new SectionType(__('Role')))->addChildren(
                RowType::withChildren($form->get('name')),
                RowType::withChildren($form->get('guard_name'))
            )
        );

		return $form;
	}
}
