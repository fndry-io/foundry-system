<?php

namespace Foundry\System\Inputs\Folder;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Illuminate\Http\Request;

class EditFolderInput extends FolderInput {

	public function view(Request $request) : FormType
    {
        $form = parent::view($request);
        $form->setTitle(__('Update Folder'));
        $form->setButtons((new SubmitButtonType(__('Update'), $form->getAction())));
        return $form;
    }
}
