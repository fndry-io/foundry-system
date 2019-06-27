<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\Core\Entities\Contracts\EntityInterface;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Entities\User;
use Foundry\System\Inputs\User\UserLoginInput;
use Foundry\System\Services\UserService;

class LoginRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;

	/**
	 * @var UserLoginInput
	 */
	protected $input;

	public static function name(): String {
		return 'foundry.system.auth.login';
	}

	/**
	 * @param $input
	 *
	 * @return \Foundry\Core\Inputs\Inputs|UserLoginInput
	 */
	public function makeInput($input) {
		return new UserLoginInput($input);
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
        return UserService::service()->login($this->input);
    }

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
    public function view() : FormType
    {
    	$form = $this->form();

	    $form->setTitle(__('Login'));
    	$form->setButtons((new SubmitButtonType(__('Log In'), $form->getAction())));
    	$form->addChildren(
    		RowType::withChildren($form->get('email')),
		    RowType::withChildren($form->get('password'))
	    );
    	return $form;
    }

}
