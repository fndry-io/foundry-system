<?php

namespace Foundry\System\Lib;

abstract class Subscriber
{
    protected $listen = [];

    public function subscribe($events)
    {
        if ($this->listen) {
            foreach ($this->listen as $class => $method) {
                $events->listen($class, get_class($this) . '@' . $method);
            }
        }
    }

}
