<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\Core\Entities\Contracts\EntityInterface;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\System\Entities\Entity;
use Foundry\System\Entities\User;
use Foundry\System\Inputs\User\UserLoginInput;
use Foundry\System\Services\UserService;
use LaravelDoctrine\ORM\Facades\EntityManager;

class LoginRequest extends FormRequest implements ViewableFormRequestInterface
{

	/**
	 * @var UserLoginInput
	 */
	protected $input;

	public static function name(): String {
		return 'foundry.system.auth.login';
	}

	/**
	 * @return string
	 */
	public static function getInputClass(): string {
		return UserLoginInput::class;
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
    		return UserService::service()->login($this->input);
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

	    $form->setTitle(__('Login'));
    	$form->setButtons((new SubmitButtonType(__('Log In'), $form->getAction())));
    	$form->addChildren(
    		RowType::withChildren($form->get('email')),
		    RowType::withChildren($form->get('password'))
	    );
    	return $form;
    }
}
