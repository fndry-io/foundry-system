<?php

namespace Foundry\System\Lib;

use Foundry\System\Repositories\ActivityRepository;
use Illuminate\Support\Facades\Auth;

trait ActivitableListener
{
    public function logActivity($model, $title, $user = null, $description = null)
    {
        if (!$user) {
            $user = Auth::user();
        }
        ActivityRepository::create($model, $title, $user, $description);
    }
}
