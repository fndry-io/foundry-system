<?php

namespace Foundry\System\Console\Commands;

use Foundry\System\Events\SyncPermissions;
use Illuminate\Console\Command;

class SyncPermissionsCommand extends Command
{
	/**
	 * The console command signature.
	 *
	 * @var string
	 */
	protected $signature = 'foundry-system:sync-permissions';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sync the permissions in the system. This will trigger the SyncPermissions event which modules can respond to in order to seed their permissions.';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		event(new SyncPermissions());
	}
}
