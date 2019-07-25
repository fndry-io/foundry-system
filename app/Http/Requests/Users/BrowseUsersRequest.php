<?php

namespace Foundry\System\Http\Requests\Users;

use Doctrine\ORM\QueryBuilder;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Http\Resources\UserResource;
use Foundry\System\Inputs\User\UsersFilterInput;
use Foundry\System\Services\UserService;

class BrowseUsersRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.users.browse';
	}

	/**
	 * @param $inputs
	 *
	 * @return \Foundry\Core\Inputs\Inputs|UsersFilterInput
	 */
	public function makeInput($inputs) {
		return new UsersFilterInput($inputs);
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
	    $result = UserService::service()->browse(function(QueryBuilder $qb) use ($inputs) {

	        $qb
			    ->addSelect('u.id', 'u.uuid', 'u.username', 'u.display_name', 'u.email', 'u.active', 'u.deleted_at', 'u.job_title')
			    ->orderBy('u.display_name', 'ASC');

		    $where = $qb->expr()->andX();

		    if ($search = $inputs->input('search', null)) {
			    $where->add($qb->expr()->orX(
				    $qb->expr()->like('u.username', ':search'),
				    $qb->expr()->like('u.display_name', ':search'),
				    $qb->expr()->like('u.email', ':search')
			    ));
			    $qb->setParameter(':search', "%" . $search . "%");
		    }

		    if (!$inputs->input('deleted', false)) {
			    $where->add($qb->expr()->isNull('u.deleted_at'));
		    }

		    if ($where->count() > 0) {
			    $qb->where($where);
		    }

	        return $qb;

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

	    $form->setTitle(__('Filter Users'));
    	$form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
    	$form->addChildren(
    		RowType::withChildren($form->get('search'))
	    );
    	return $form;
    }


}
