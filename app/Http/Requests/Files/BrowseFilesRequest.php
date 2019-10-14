<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\Core\Requests\Traits\HasReference;
use Foundry\Core\Requests\Traits\IsBrowseRequest;
use Foundry\System\Http\Resources\File;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Services\FileService;
use LaravelDoctrine\ORM\Facades\EntityManager;

class BrowseFilesRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;
	use HasReference;

	public static function name(): String {
		return 'foundry.system.files.browse';
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
	 * @param $inputs
	 *
	 * @return \Foundry\Core\Inputs\Inputs|SearchFilterInput
	 */
	public function makeInput($inputs) {
		return new SearchFilterInput($inputs);
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
		$entity = $this->getReference();

		$page = $this->input('page', 1);
		$limit = $this->input('limit', 20);

		return FileService::service()->browse($entity, $this->getInput(), $page, $limit)->asResource(File::class, true);
	}

	/**
	 * @return FormType
	 */
	function form($params = []): FormType {
	    return parent::form([
            'reference_type' => $this->input('reference_type'),
            'reference_id' => $this->input('reference_id')
        ]);
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		$form->setTitle(__('Filter'));
		$form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
		$form->addChildren(
			RowType::withChildren($form->get('search'))
		);
		return $form;
	}

}
