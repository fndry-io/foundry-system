<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\Core\Inputs\SimpleInputs;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\Core\Requests\Traits\IsBrowseRequest;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Services\UserService;

class BrowseUsersRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;
	use IsBrowseRequest;

	public static function name(): String {
		return 'foundry.system.users.browse';
	}

	/**
	 * @param $inputs
	 *
	 * @return \Foundry\Core\Inputs\Inputs|SimpleInputs
	 */
	public function makeInput($inputs) {
		return (new SimpleInputs($inputs, InputTypeCollection::fromTypes([
			(new TextInputType('search', 'Search', false)),
			(new ChoiceInputType('deleted', 'Deleted', false)),
			(new ChoiceInputType('archived', 'Archived', false))
		])));
	}

	/**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	//todo update to use the permissions
        return !!($this->user());
    }

	/**
	 * Handle the request
	 *
	 * @see UserResource
	 * @return Response
	 */
    public function handle() : Response
    {
	    $inputs = $this->input;

	    $page = $this->input('page', 1);
	    $limit = $this->input('limit', 20);

	    $paginator = UserService::service()->browse($inputs, $page, $limit );

	    $result = $this->makeBrowseResource($paginator, $page, $limit);

	    return Response::success($result);
    }

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
    public function view() : FormType
    {
    	$form = $this->form();

	    $form->setTitle(__('Filter Users'));
    	$form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
    	$form->addChildren(
    		RowType::withChildren($form->get('search'))
	    );
    	return $form;
    }


}
