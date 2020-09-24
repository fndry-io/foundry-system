<?php

namespace Foundry\System\Console\Commands;

use Foundry\System\Events\SyncPermissions;
use Foundry\System\Events\SyncPickLists;
use Foundry\System\Events\SyncSettings;
use Foundry\System\Events\SyncSystem;
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
        event(new SyncSystem($this->argument('type')));
	}
}
