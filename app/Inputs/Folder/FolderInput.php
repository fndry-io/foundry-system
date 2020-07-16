<?php

namespace Foundry\System\Inputs\Folder;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Traits\ViewableInput;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\ViewableInputInterface;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\Folder\Types\Name;
use Foundry\System\Inputs\Types\Folder;
use Foundry\System\Inputs\Types\ReferenceId;
use Foundry\System\Inputs\Types\ReferenceType;
use Illuminate\Http\Request;

/**
 * Class FolderInput
 *
 * @package Foundry\System\Inputs
 *
 */
class FolderInput extends Inputs implements ViewableInputInterface
{

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Name::input(),
			ReferenceType::input(),
			ReferenceId::input(),
			Folder::input()->setName('parent')->addRule('exists:system_folders,id')
		]);
	}

    public function form(Request $request, array $params = []): FormType {

        $form = new FormType( uniqid('form_') );
        $form->attachInputs( $this );
        $form->setAction( '/' . $request->route()->uri() );
        $form->setParams( $request->route()->parameters() );

        $form->setParams(array_merge([
            'reference_type' => $request->input('reference_type'),
            'reference_id' => $request->input('reference_id'),
            'parent' => $request->input('parent')
        ], $request->route()->parameters()));

        $form->setRequest( $request );
        return $form;
    }

    public function view(Request $request) : FormType
    {
        $form = $this->form($request);

        $form->addChildren((new SectionType(__('Folder Name')))->addChildren(
            RowType::withChildren($form->get('name'))
        ));

        return $form;
    }

}
