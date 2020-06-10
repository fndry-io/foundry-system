<?php

namespace Foundry\System\Http\Requests\Folders;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\Core\Requests\Traits\HasReference;
use Foundry\System\Inputs\Folder\FolderInput;
use Foundry\System\Services\FolderService;

class AddFolderRequest extends FolderRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;
	use HasReference;

	public static function name(): String {
		return 'system.folders.add.folder';
	}

	/**
	 * @param $inputs
	 *
	 * @return \Foundry\Core\Inputs\Inputs|FolderInput
	 */
	public function makeInput($inputs) {
		$inputs['parent'] = $this->getEntity()->getKey();
		return new FolderInput($inputs);
	}

	/**
	 * Determine if the Folder is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('add folders'));
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
        $reference = $this->findReference($this->input('reference_type'), $this->input('reference_id'));
		return FolderService::service()->add($this->getInput(), $this->getEntity(), $reference);
	}

    /**
     * @param array $params
     * @return FormType
     */
	function form($params = []): FormType {

		$form = new FormType( static::name() );

        $form->attachInputs( $this );

        $form->setAction( resourceUri( $this::name()) );
        $form->setParams(array_merge([
            '_entity' => $this->getEntity()->getKey(),
            'reference_type' => $this->input('reference_type'),
            'reference_id' => $this->input('reference_id'),
            'parent' => $this->input('parent')
        ], $params));
        $form->setRequest( $this );

        return $form;
	}

	/**
	 * Make a viewable DocType for the request
	 *
     * @return FormType
     * @throws \Exception
     */
	public function view() : FormType
	{
		$form = $this->form();

		$form->setTitle(__('Create Folder'));
		$form->setButtons((new SubmitButtonType(__('Create'), $form->getAction())));

		$form->addChildren((new SectionType(__('Folder Name')))->addChildren(
			RowType::withChildren($form->get('name'))
		));

		return $form;
	}
}
