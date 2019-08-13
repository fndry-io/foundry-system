<?php

namespace Foundry\System\Http\Requests\Folders;

use Foundry\Core\Inputs\SimpleInputs;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\Types\File;

class AddFileRequest extends FolderRequest implements ViewableFormRequestInterface
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
		/**
		 * The service in this request only needs to return a success as the FileInputType used in the view is already
		 * adding the file to the folder
		 */
		return Response::success();
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

		$file->setParams([
			'reference_type' => $this->input('reference_type'),
			'reference_id' => $this->input('reference_id')
		]);

		$form->setTitle(__('Add File'));
		$form->setButtons((new SubmitButtonType(__('Done'), $form->getAction())));
		$form->addChildren(
			(new SectionType(__('Select Files')))->addChildren($file)
		);
		return $form;
	}

}
