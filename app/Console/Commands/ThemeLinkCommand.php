<?php

namespace Foundry\System\Console\Commands;

use Illuminate\Console\Command;

class ThemeLinkCommand extends Command
{
	/**
	 * The console command signature.
	 *
	 * @var string
	 */
	protected $signature = 'foundry:link-theme {theme : The theme name as vendor/package}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a symbolic link from "public/themes/[theme name]" to the themes public directory';

	/**
	 * Execute the console command.
	/**
	 * @throws \Illuminate\Contracts\Container\BindingResolutionException
	 */
	public function handle()
	{
		$theme = explode("/", $this->argument('theme'));

		if (sizeof($theme) !== 2) {
			return $this->error('The theme supplied is invalid.');
		}

		$theme_path = base_path('themes' . DIRECTORY_SEPARATOR . $theme[0] . DIRECTORY_SEPARATOR . $theme[1] . DIRECTORY_SEPARATOR . 'public');
		if (!file_exists($theme_path)) {
			return $this->error(sprintf('The source "%s" directory does not exists.', $theme_path));
		}

		$public_path = public_path('themes' . DIRECTORY_SEPARATOR . $theme[1]);

		if (file_exists($public_path)) {
			return $this->error(sprintf('The "%s" directory already exists.', 'themes' . DIRECTORY_SEPARATOR . $theme[1]));
		}

		$this->laravel->make('files')->link(
			$theme_path, $public_path
		);

		$this->info(sprintf('The "%s" directory has been linked.', $public_path));
	}
}
