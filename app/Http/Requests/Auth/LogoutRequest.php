<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\TagType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\User\UserLogoutInput;
use Foundry\System\Services\UserService;

class LogoutRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.auth.logout';
	}

	/**
	 * The input class for this form request
	 *
	 * @return string|null
	 */
	static function getInputClass() {
		return UserLogoutInput::class;
	}

	/**
     * Determine if the user is authorized to make this request.
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
        return UserService::service()->logout($this->input('guard'));
    }

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();
		$form->setTitle(__('Log Out'));
		$form->addChildren((new TagType('div', __('You will now be logged out of the session'))));
		$form->setButtons((new SubmitButtonType(__('Log Out'), $form->getAction())));
		return $form;
	}
}
