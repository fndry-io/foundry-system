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
use Foundry\System\Inputs\Folder\FolderEditInput;
use Foundry\System\Services\FolderService;

class EditFolderRequest extends FolderRequest implements ViewableFormRequestInterface, EntityRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.folders.edit';
	}

	/**
	 * @param $inputs
	 *
	 * @return string
	 */
	public function makeInput($inputs) {
		return new FolderEditInput($inputs);
	}

	/**
	 * Determine if the Folder is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('edit folders'));
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
		return FolderService::service()->edit($this->input, $this->getEntity());
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		$form->setTitle(__('Update Folder'));
		$form->setButtons((new SubmitButtonType(__('Update'), $form->getAction())));

		$form->addChildren((new SectionType(__('Folder Name')))->addChildren(
			RowType::withChildren($form->get('name'))
		));

		return $form;
	}

}
