<?php

namespace Foundry\System\Listeners;

use Foundry\System\Events\FolderCreated;
use Foundry\System\Events\FolderDeleted;
use Foundry\System\Events\FolderRestored;
use Foundry\System\Events\FolderUpdated;
use Foundry\System\Lib\ActivitySubscriber;

/**
 * Class FolderActivitySubscriber
 *
 *
 *
 * @package Foundry\System\Listeners
 */
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
            $this->logActivity($event->folder, __('added file ***:name***', ['name' => $event->folder->file->original_name]));
        } else {
            $this->logActivity($event->folder, __('added folder ***:name***', ['name' => $event->folder->name]));
        }

    }

    public function handleFolderUpdated($event)
    {
        if ($event->folder->isFile()) {
            $this->logActivity($event->folder, __('updated file ***:name***', ['name' => $event->folder->file->original_name]));
        } else {
            $this->logActivity($event->folder, __('updated folder ***:name***', ['name' => $event->folder->name]));
        }
    }

    public function handleFolderDeleted($event)
    {
        if ($event->folder->isFile()) {
            $this->logActivity($event->folder, __('deleted file ***:name***', ['name' => $event->folder->file->original_name]));
        } else {
            $this->logActivity($event->folder, __('deleted folder ***:name***', ['name' => $event->folder->name]));
        }
    }

    public function handleFolderRestored($event)
    {
        if ($event->folder->isFile()) {
            $this->logActivity($event->folder, __('restored file ***:name***', ['name' => $event->folder->file->original_name]));
        } else {
            $this->logActivity($event->folder, __('restored folder ***:name***', ['name' => $event->folder->name]));
        }
    }

}
