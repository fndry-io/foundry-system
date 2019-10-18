<?php

namespace Foundry\System\Listeners;

use Foundry\System\Events\FolderCreated;
use Foundry\System\Events\FolderDeleted;
use Foundry\System\Events\FolderRestored;
use Foundry\System\Events\FolderUpdated;
use Foundry\System\Lib\ActivitySubscriber;

class FolderActivitySubscriber extends ActivitySubscriber
{

    protected $listen = [
        FolderCreated::class              => 'handleFolderCreated',
        FolderUpdated::class              => 'handleFolderUpdated',
        FolderDeleted::class              => 'handleFolderDeleted',
        FolderRestored::class             => 'handleFolderRestored'
    ];

    public function handleFolderCreated($event)
    {
        if ($event->folder->isFile()) {
            $this->logActivity($event->folder, __('added the file ***:name***', ['name' => $event->folder->name]));
        } else {
            $this->logActivity($event->folder, __('added the folder ***:name***', ['name' => $event->folder->name]));
        }

    }

    public function handleFolderUpdated($event)
    {
        if ($event->folder->isFile()) {
            $this->logActivity($event->folder, __('updated the file ***:name***', ['name' => $event->folder->name]));
        } else {
            $this->logActivity($event->folder, __('updated the folder ***:name***', ['name' => $event->folder->name]));
        }
    }

    public function handleFolderDeleted($event)
    {
        if ($event->folder->isFile()) {
            $this->logActivity($event->folder, __('deleted the file ***:name***', ['name' => $event->folder->name]));
        } else {
            $this->logActivity($event->folder, __('deleted the folder ***:name***', ['name' => $event->folder->name]));
        }
    }

    public function handleFolderRestored($event)
    {
        if ($event->folder->isFile()) {
            $this->logActivity($event->folder, __('restored the file ***:name***', ['name' => $event->folder->name]));
        } else {
            $this->logActivity($event->folder, __('restored the folder ***:name***', ['name' => $event->folder->name]));
        }
    }

}
