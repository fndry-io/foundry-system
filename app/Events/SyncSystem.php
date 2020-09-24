<?php

namespace Foundry\System\Events;

class SyncSystem
{
    public $type;

    public function __construct($type = null)
    {
        $this->type = $type;
    }
}
