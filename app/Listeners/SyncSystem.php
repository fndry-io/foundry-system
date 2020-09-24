<?php

namespace Foundry\System\Listeners;

class SyncSystem
{
    public function handle(\Foundry\System\Events\SyncSystem $event)
    {
        if (empty($event->type) || $event->type === 'permissions') {
            event(new \Foundry\System\Events\SyncPermissions());
        }
        if (empty($event->type) || $event->type === 'picklists') {
            event(new \Foundry\System\Events\SyncPickLists());
        }
        if (empty($event->type) || $event->type === 'settings') {
            event(new \Foundry\System\Events\SyncSettings());
        }
    }
}
