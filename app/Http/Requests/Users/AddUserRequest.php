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
use Foundry\System\Inputs\User\UserInput;
use Foundry\System\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
        return ($this->user() && $this->user()->can('add users'));
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
			(new SectionType(__('Details')))->addChildren(
				RowType::withChildren($form->get('username')->setAutocomplete(false), $form->get('display_name')->setAutocomplete(false)),
				RowType::withChildren($form->get('email')->setAutocomplete(false))
			)
		);

		$form->addChildren(
			(new SectionType(__('Password')))->addChildren(
				RowType::withChildren($form->get('password')->setAutocomplete(false)->setRequired(true), $form->get('password_confirmation')->setAutocomplete(false)->setRequired(true))
			)
		);

		$form->addChildren(
			(new SectionType(__('Job Title & Position')))->addChildren(
				RowType::withChildren($form->get('job_title')->setAutocomplete(false), $form->get('job_department')->setAutocomplete(false)),
				RowType::withChildren($form->get('supervisor')->setAutocomplete(false))
			)
		);

		if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
			$children = [];
			$children[] = $form->get('active');

			if (Auth::user()->isSuperAdmin()) {
				$children[] = $form->get('super_admin');
			}

            $form->addChildren(
                (new SectionType(__('Access'), __('Controls the access this user has to the system.')))->addChildren(
                    RowType::withChildren(...$children),
                    RowType::withChildren($form->get('roles'))
                )
            );
		}

		return $form;
	}
}
