<?php

namespace Foundry\System\Console\Commands;

use Illuminate\Console\Command;

class SymLinkCommand extends Command
{
	/**
	 * The console command signature.
	 *
	 * @var string
	 */
	protected $signature = 'foundry:symlink {public : The public directory/uri} {target : The folder to link to}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a symbolic link from a public directory to another directory in the system';

	/**
	 * Execute the console command.
	/**
	 * @throws \Illuminate\Contracts\Container\BindingResolutionException
	 */
	public function handle()
	{
		$target = $this->argument('target');
		$target_path = base_path($target);
		if (!file_exists($target_path)) {
			return $this->error(sprintf('The "%s" directory does not exist.', $target));
		}

		$pub = $this->argument('public');
		$public_path = public_path($pub);
		if (file_exists($public_path)) {
			return $this->error(sprintf('The "%s" directory already exists.', $pub));
		}

		$this->laravel->make('files')->link(
			$target_path, $public_path
		);

		$this->info(sprintf('The "%s" directory has been linked.', $public_path));
	}
}
