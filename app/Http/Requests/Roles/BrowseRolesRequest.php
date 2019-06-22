<?php

namespace Foundry\System\Http\Requests\Roles;

use Doctrine\ORM\QueryBuilder;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Http\Resources\RoleResource;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Services\RoleService;
use Illuminate\Support\Collection;

class BrowseRolesRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.roles.browse';
	}

	/**
	 * @return string
	 */
	static function getInputClass(): string {
		return SearchFilterInput::class;
	}

	/**
     * Determine if the role is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	//todo update to use the permissions
	    return !!(auth_user());
    }

	/**
	 * Handle the request
	 *
	 * @see RoleResource
	 * @return Response
	 */
    public function handle() : Response
    {
    	$response = $this->input->validate();
    	if ($response->isSuccess()) {

		    $result = RoleService::service()->browse(function(QueryBuilder $qb) {

			    return $qb
				    ->addSelect('r.id', 'r.name')
				    ->orderBy('r.name', 'ASC');

		    }, $this->input('page', 1), $this->input('limit', 20) );

		    return Response::success($result);
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

	    $form->setTitle(__('Filter Roles'));
    	$form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
    	$form->addChildren(
    		RowType::withChildren($form->get('search'))
	    );
    	return $form;
    }
}
