<?php

namespace Foundry\System\Http\Controllers;

use Foundry\Core\Requests\Response;
use Foundry\System\Events\SyncPermissions;
use Foundry\System\Http\Requests\Permissions\SyncPermissionsRequest;
use Illuminate\Events\Dispatcher;

class PermissionsController extends Controller
{
    public function sync(SyncPermissionsRequest $request, Dispatcher $event)
    {
        $event->dispatch(new SyncPermissions());

        return Response::success()->withMessage("Permissions Synchronized");
    }
}
