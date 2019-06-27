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
	 * @param $inputs
	 *
	 * @return \Foundry\Core\Inputs\Inputs|SearchFilterInput
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
    	//todo update to use the permissions
	    return !!($this->user());
    }

	/**
	 * Handle the request
	 *
	 * @see RoleResource
	 * @return Response
	 */
    public function handle() : Response
    {
	    $result = RoleService::service()->browse(function(QueryBuilder $qb) {

		    return $qb
			    ->addSelect('r.id', 'r.name')
			    ->orderBy('r.name', 'ASC');

	    }, $this->input('page', 1), $this->input('limit', 20) );

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

	    $form->setTitle(__('Filter Roles'));
    	$form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
    	$form->addChildren(
    		RowType::withChildren($form->get('search'))
	    );
    	return $form;
    }
}
