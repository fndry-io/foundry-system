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
use Foundry\System\Http\Resources\Role;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Services\RoleService;

class BrowseRolesRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.roles.browse';
	}

	/**
	 * @param $inputs
	 *
	 * @return SearchFilterInput
	 */
	public function makeInput($inputs) {
		return new SearchFilterInput($inputs);
	}

	/**
     * Determine if the role is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ($this->user() && $this->user()->can('browse roles'));
    }

	/**
	 * Handle the request
	 *
	 * @see Role
	 * @return Response
	 */
    public function handle() : Response
    {
	    $inputs = $this->input;

	    $page = $this->input('page', 1);
	    $limit = $this->input('limit', 20);

	    return RoleService::service()->browse($inputs, $page, $limit )->asResource(Role::class, true);
    }

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
    public function view() : FormType
    {
    	$form = $this->form();

	    $form->setTitle(__('Filter Roles'));
    	$form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
    	$form->addChildren(
    		RowType::withChildren($form->get('search'))
	    );
    	return $form;
    }
}
