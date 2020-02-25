<?php

namespace Foundry\System\Providers;

use Foundry\System\Events\SyncPermissions as SyncPermissionsEvent;
use Foundry\System\Events\SyncPickLists;
use Foundry\System\Listeners\FolderActivitySubscriber;
use Foundry\System\Listeners\SyncPermissions as SyncPermissionsListener;
use Foundry\System\Listeners\SyncPickLists as SyncPickListsListener;
use Foundry\System\Listeners\SyncSettings as SyncSettingsListener;
use Foundry\System\Events\SyncSettings;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SyncPermissionsEvent::class => [
            SyncPermissionsListener::class
        ],
        SyncPickLists::class => [
            SyncPickListsListener::class
        ],
        SyncSettings::class => [
            SyncSettingsListener::class
        ]
    ];

    /**
     * The event subscribers
     *
     * @var array
     */
    protected $subscribe = [
        FolderActivitySubscriber::class
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

}
