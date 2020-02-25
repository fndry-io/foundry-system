<?php

namespace Foundry\System\Events;

class SyncSystem
{
    public $type;

    public function __construct($type = null)
    {
        $this->type = $type;
        $syncs = [
            'permissions' => SyncPermissions::class,
            'picklists' => SyncPickLists::class,
            'settings' => SyncSettings::class
        ];
        if($type) {
            if (isset($syncs[$type])) event(new $syncs[$type]);
        } else {
            foreach ($syncs as $key => $class){
                event(new $class());
            }
        }
    }
}
