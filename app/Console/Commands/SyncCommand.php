<?php

namespace Foundry\System\Console\Commands;

use Foundry\System\Events\SyncPermissions;
use Foundry\System\Events\SyncPickLists;
use Foundry\System\Events\SyncSettings;
use Illuminate\Console\Command;

class SyncCommand extends Command
{
	/**
	 * The console command signature.
	 *
	 * @var string
	 */
	protected $signature = 'foundry:sync {type? : Sync type to perform}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Sync the system. This will trigger the Sync event which modules can respond to in order to sync details. Types: permissions = Sync permissions; picklists = Sync pick lists. Leave out to sync all.";

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
	    $type = $this->argument('type');

	    $syncs = [
	        'permissions' => SyncPermissions::class,
            'picklists' => SyncPickLists::class,
            'settings' => SyncSettings::class
        ];

	    if($type){
	        event(new $syncs[$type]);
        }else{
	        foreach ($syncs as $key => $class){
	            event(new $class());
            }
        }
	}
}
