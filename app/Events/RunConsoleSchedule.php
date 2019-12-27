<?php

namespace Foundry\System\Events;

use Illuminate\Console\Scheduling\Schedule;

class RunConsoleSchedule
{
    public $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }
}
