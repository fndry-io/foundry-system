<?php

namespace Foundry\System\Http\Requests\Folders;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\Folder\FolderInput;
use Foundry\System\Services\FolderService;

class AddFolderRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.folders.add';
	}

	/**
	 * @param $inputs
	 *
	 * @return \Foundry\Core\Inputs\Inputs|FolderInput
	 */
	public function makeInput($inputs) {
		return new FolderInput($inputs);
	}

	/**
	 * Determine if the Folder is authorized to make this request.
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
		return FolderService::service()->add($this->input);
	}

	/**
	 * @return FormType
	 */
	public function form(): FormType {
		return parent::form()->setAction( route( $this::name(), [
			'reference_type' => $this->input('reference_type'),
			'reference_id' => $this->input('reference_id'),
			'parent' => $this->input('parent')
		] , false) );
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		$form->setTitle(__('Create Folder'));
		$form->setButtons((new SubmitButtonType(__('Create'), $form->getAction())));
		$form->addChildren(
			RowType::withChildren($form->get('name'))
		);
		return $form;
	}
}
