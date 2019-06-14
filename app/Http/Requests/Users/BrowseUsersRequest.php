<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\Core\Entities\Contracts\EntityInterface;
use Foundry\Core\Inputs\Types\DocType;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\System\Entities\Entity;
use Foundry\System\Entities\User;
use Foundry\System\Http\Resources\UserResource;
use Foundry\System\Inputs\User\UsersFilterInput;
use Foundry\System\Services\UserService;
use Illuminate\Support\Collection;

class BrowseUsersRequest extends FormRequest implements ViewableFormRequestInterface
{

	public static function name(): String {
		return 'foundry.system.users.browse';
	}

	/**
	 * @return string
	 */
	public static function getInputClass(): string {
		return UsersFilterInput::class;
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
    	//todo update to use the permissions
        return true;
    }

	/**
	 * Handle the request
	 *
	 * @see UserResource
	 * @return Response
	 */
    public function handle() : Response
    {
    	$response = $this->input->validate();
    	if ($response->isSuccess()) {
    		$results = UserService::service()->filter(function($builder){
    			return $builder;
		    });

		    $data = $results->toArray();
		    $data['data'] = UserResource::collection(Collection::make($results->items()));

    		return Response::success($data);
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
    	$form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
    	$form->addChildren(
    		RowType::withChildren($form->get('search'))
	    );
    	return $form;
    }
}
