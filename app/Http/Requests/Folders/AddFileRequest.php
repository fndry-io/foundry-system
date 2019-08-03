<?php

namespace Foundry\System\Http\Requests\Folders;

use Foundry\Core\Inputs\SimpleInputs;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Inputs\Types\File;
use Foundry\System\Services\FolderService;

class AddFileRequest extends FolderRequest implements ViewableFormRequestInterface, EntityRequestInterface
{

	public static function name(): String {
		return 'foundry.system.folders.add.file';
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
		return FolderService::service()->browse($this->getEntity(), new SearchFilterInput());
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		$file = File::input()->setMultiple(true);
		$input = new SimpleInputs([], InputTypeCollection::fromTypes([ $file ]));

		$form->attachInputCollection( $input->types() );
		$form->setValues( $this->only( $input->keys() ) );

		$file = $form->get('file');
		if ($this->getEntity()) {
			$file->setFolder($this->getEntity());
		}

		$form->setTitle(__('Add File'));
		$form->setButtons((new SubmitButtonType(__('Continue'), $form->getAction())));
		$form->addChildren(
			$file
		);
		return $form;
	}

}
