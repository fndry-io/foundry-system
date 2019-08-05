<?php

namespace Foundry\System\Http\Requests\PickLists;

use Doctrine\ORM\QueryBuilder;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Services\PickListService;

class BrowsePickListsRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
    use HasInput;

	public static function name(): String {
		return 'foundry.system.pick-lists.browse';
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
	 * Determine if the item is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return !!($this->user());
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
        $inputs = $this->getInput();

        $result = PickListService::service()->browse(function(QueryBuilder $qb) use ($inputs) {

            $qb
                ->addSelect('picklist')
                ->orderBy('picklist.label', 'ASC');

            return $qb;

        }, $this->input('page', 1), $this->input('limit', 20) );

        return Response::success($result);
	}

	/**
	 * @return FormType
	 */
    public function view() : FormType
    {
        $form = $this->form();

        $form->setTitle(__('Pick Lists'));
        $form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
        $form->addChildren(
            RowType::withChildren($form->get('search'))
        );
        return $form;
    }

}
