<?php

namespace Foundry\System\Events;

use Foundry\Core\Inputs\Inputs;

class InputCreated
{
    public Inputs $inputs;

    public function __construct(Inputs $inputs)
    {
        $this->inputs = $inputs;
    }
}
