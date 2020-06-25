<?php

namespace Foundry\System\Events;

use Foundry\Core\Inputs\Types\FormType;

class FormCreated
{
    public FormType $formType;

    public function __construct(FormType $formType)
    {
        $this->formType = $formType;
    }
}
