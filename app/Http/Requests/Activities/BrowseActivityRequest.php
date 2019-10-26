<?php

namespace Foundry\System\Http\Requests\Activities;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\Core\Requests\Traits\HasNode;
use Foundry\Core\Requests\Traits\HasReference;
use Foundry\System\Http\Resources\Activity;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Services\ActivityService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BrowseActivityRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;
	use HasReference;
	use HasNode;

	public static function name(): String {
        return 'activities.activity.browse';
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
     * Determine if the board is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
	    return ($this->user() && $this->user()->can('browse activities'));
    }

	/**
	 * Handle the request
	 *
	 * @see BoardResource
	 * @return Response
	 */
    public function handle() : Response
    {
        if (!$entity = $this->findReference($this->input('reference_type'), $this->input('reference_id'))) {
            $entity = $this->getNode();
        }

        if (!$entity) {
            throw new NotFoundHttpException(__('Associated entity not found'));
        }

        $page = $this->input('page', 1);
        $limit = $this->input('limit', 20);

        return ActivityService::service()->browse($entity, $this->getInput(), $page, $limit)->asResource(Activity::class, true);
    }

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
    public function view() : FormType
    {
    	$form = $this->form();

	    $form->setTitle(__('Meetings'));
    	$form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
    	$form->addChildren(
    		RowType::withChildren($form->get('search'))
	    );
    	return $form;
    }

}
