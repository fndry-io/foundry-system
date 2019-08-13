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
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Services\FileService;
use LaravelDoctrine\ORM\Facades\EntityManager;

class BrowseFilesRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;

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
		$entity = null;
		if (($reference_type = $this->input('reference_type')) && ($reference_id = $this->input('reference_id'))) {
			$entity = EntityManager::getRepository($reference_type)->find($reference_id);
		}
		if (!$entity) {
			return Response::error('Reference not found', 404);
		}
		return FileService::service()->browse($entity, $this->getInput());
	}
	/**
	 * @return FormType
	 */
	public function form(): FormType {

		$form   = new FormType( static::name() );
		$params = [
			'reference_type' => $this->input('reference_type'),
			'reference_id' => $this->input('reference_id')
		];

		if ( $this instanceof InputInterface) {
			$form->attachInputCollection( $this->getInput()->getTypes() );
			$form->setValues( $this->getInput()->getTypes()->values() );
		}

		$form->setAction( route( $this::name(), $params, false) );
		$form->setRequest( $this );

		return $form;
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
