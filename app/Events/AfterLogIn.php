<?php

namespace Foundry\System\Events;

use Illuminate\Queue\SerializesModels;

class AfterLogIn
{
    use SerializesModels;

    public $data;

    /**
     * @param array $data
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
}
