<?php

namespace Foundry\System\Listeners;

use Foundry\System\Lib\PickListSeeder;
use Foundry\System\Models\Permission;
use Foundry\System\Models\Role;

class SyncPickLists
{
    public function handle()
    {
        /*PickListSeeder::seed([
            [
                'label' => 'Status',
                'identifier' => 'identifier',
                'items' => [
                    [
                        'label' => 'In Progress',
                        'identifier' => 'in-progress',
                        'is_default' => true
                    ],
                    "won" => "Won",
                    "lost" => "Lost",
                ]
            ]

        ]);*/
    }
}
