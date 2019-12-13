<?php

namespace Foundry\System\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class AppScope
{
    public function handle($request, Closure $next)
    {
        $scope = null;

        //remove it from the session if not enabled
        if (!config('scope.enabled', false) && Session::exists(config('scope.session_key', 'scope'))) {
            Session::remove(config('scope.session_key', 'scope'));
            $next($request);
        }

        if (config('scope.enabled', false) && ($id = config('scope.id', null))) {
            $scope = \Foundry\System\Models\AppScope::query()->find($id);
        }
        else if ($id = Session::get(config('scope.session_key', 'scope'))) {
            $scope = \Foundry\System\Models\AppScope::query()->find($id);
        }
        //todo update this to also allow it to extract the scope off a sub domain
        //todo update this to also allow it to extract the scope off a http header

        if ($scope) {
            Session::put(config('scope.session_key', 'scope'), $scope);
        } else {
            //todo consider redirecting the user to some scope picking page
            Session::remove(config('scope.session_key', 'scope'));
        }

        return $next($request);
    }
}
