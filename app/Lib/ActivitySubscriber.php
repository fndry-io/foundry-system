<?php

namespace Foundry\System\Lib;

use Foundry\System\Repositories\ActivityRepository;
use Illuminate\Support\Facades\Auth;

abstract class ActivitySubscriber extends Subscriber
{
    use ActivitableListener;
}
