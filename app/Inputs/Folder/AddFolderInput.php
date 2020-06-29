<?php

namespace Foundry\System\Inputs\Folder;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Illuminate\Http\Request;

class AddFolderInput extends FolderInput {

	public function view(Request $request) : FormType
    {
        $form = parent::view();
        $form->setTitle(__('Create Folder'));
        $form->setButtons((new SubmitButtonType(__('Create'), $form->getAction())));
        return $form;
    }

}
