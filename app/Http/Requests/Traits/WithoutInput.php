<?php

namespace Foundry\System\Http\Requests\Traits;

use Foundry\Core\Inputs\Types\DocType;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\SubmitButtonType;

trait WithoutInput {


	/**
	 * @return string|null
	 */
	public static function getInputClass() {
		return null;
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();
		$form->setButtons((new SubmitButtonType(__('Submit'), $form->getAction())));
		return $form;
	}
}