<?php

namespace Foundry\System\Http\Requests\PickLists;

use Doctrine\ORM\QueryBuilder;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Services\PickListItemService;

class BrowsePickListItemsRequest extends PickListRequest implements ViewableFormRequestInterface, InputInterface
{

    use HasInput;

	public static function name(): String {
		return 'foundry.system.pick-lists.items.browse';
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
                ->orderBy('picklist_item.label', 'ASC');

	        $where = $qb->expr()->andX();

	        if ($search = $inputs->input('search')) {
		        $where->add($qb->expr()->orX(
			        $qb->expr()->like('picklist_item.label', ':search')
		        ));
		        $qb->setParameter(':search', "%" . $search . "%");
	        }

	        $where->add($qb->expr()->eq('picklist_item.picklist', ':picklist'));
	        $qb->setParameter('picklist', $this->getEntity());

	        $qb->where($where);

            return $qb;

        }, $this->input('page', 1), $this->input('limit', 20) );

        return Response::success($result);
	}


    public function view() : FormType
    {
        $form = $this->form();

        $form->setTitle(__('Update Pick List Item'));
        $form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
        $form->addChildren(
            RowType::withChildren($form->get('search'))
        );
        return $form;
    }

}
