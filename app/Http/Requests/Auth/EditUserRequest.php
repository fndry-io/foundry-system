<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Http\Resources\AuthUser;
use Foundry\System\Inputs\User\UserEditInput;
use Foundry\System\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EditUserRequest extends UserRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.auth.edit';
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

	/**
	 * @return array
	 */
	public function rules() {
		$this->setEntity($this->user());
		$rules = $this->input->rules();
		if ($entity = $this->getEntity()) {
			$rules['email'] = [
				'required',
				'email',
				Rule::unique('users', 'email')->ignore($entity->getKey())
			];
			$rules['username'] = [
				'required',
				'username',
				Rule::unique('users', 'username')->ignore($entity->getKey())
			];
			$rules['display_name'] = [
				'required',
				Rule::unique('users', 'display_name')->ignore($entity->getKey())
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
		return UserService::service()->edit($this->input, $this->getEntity())->asResource(AuthUser::class);
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		//$form->setValues(['password' => null, 'password_confirmation' => null]);
        $entity = $this->getEntity();

        $image = $form->get('profile_image');
        if ($entity->profile_image) {
            $image->setFiles([$entity->profile_image->only('id', 'url', 'original_name', 'type', 'size')]);
        }

		$form->setTitle(__('Edit User'));
		$form->setButtons((new SubmitButtonType(__('Save'), $form->getAction())));
		$form->addChildren((new SectionType(__('Details')))->addChildren(
			RowType::withChildren($form->get('username')->setAutocomplete(false), $form->get('display_name')->setAutocomplete(false)),
			RowType::withChildren($form->get('email')->setAutocomplete(false)),
            RowType::withChildren($image)
		));

		$form->addChildren(
			(new SectionType(__('Password'), __('If you need to change the password set the values below or leave them blank to leave the password as is.')))->addChildren(
				RowType::withChildren($form->get('password')->setAutocomplete(false), $form->get('password_confirmation')->setAutocomplete(false))
			)
		);

		return $form;
	}
}
