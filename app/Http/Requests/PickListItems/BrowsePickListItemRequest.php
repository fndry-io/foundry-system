<?php

namespace Foundry\System\Http\Requests\PickListItems;

use Doctrine\ORM\QueryBuilder;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Services\PickListItemService;


class BrowsePickListItemRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{

    use HasInput;

	public static function name(): String {
		return 'picklistitems.picklistitems.browse';
	}


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

        $result = PickListItemService::service()->browse(function(QueryBuilder $qb) use ($inputs) {

            $qb
                ->addSelect('picklist_item')
                ->orderBy('picklist_item.name', 'ASC');

            return $qb;

        }, $this->input('page', 1), $this->input('limit', 20) );

        return Response::success($result);
	}


    public function view() : FormType
    {
        $form = $this->form();

        $form->setTitle(__('Update Picklist Item'));
        $form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
        $form->addChildren(
            RowType::withChildren($form->get('search'))
        );
        return $form;
    }

}
