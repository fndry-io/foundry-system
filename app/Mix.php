<?php

namespace Foundry\System;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

class Mix
{
    /**
     * Get the path to a versioned Mix file.
     *
     * @param $name The theme or module name. I.E. provider/name
     * @param $path The path desired
     * @param string $manifestDirectory
     * @param string $base the base root folder directory
     * @return HtmlString|string
     * @throws Exception
     */
    public function __invoke($name, $path, $manifestDirectory = '', $base = '/themes')
    {
        static $manifests = [];

        $manifestDirectory = $base . DIRECTORY_SEPARATOR . $name . $manifestDirectory;

        if (! Str::startsWith($path, '/')) {
            $path = "/{$path}";
        }

        if ($manifestDirectory && ! Str::startsWith($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        if (file_exists(public_path($manifestDirectory.'/hot'))) {
            $url = rtrim(file_get_contents(public_path($manifestDirectory.'/hot')));

            if (Str::startsWith($url, ['http://', 'https://'])) {
                return new HtmlString(Str::after($url, ':').$path);
            }

            return new HtmlString("//localhost:8080{$path}");
        }

        $manifestPath = public_path($manifestDirectory.'/mix-manifest.json');

        if (! isset($manifests[$manifestPath])) {
            if (! file_exists($manifestPath)) {
                throw new Exception(sprintf('The Mix manifest %s does not exist.', $manifestPath));
            }

            $manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
        }

        $manifest = $manifests[$manifestPath];

        if (! isset($manifest[$path])) {
            $exception = new Exception("Unable to locate Mix file {$path}.");

            if (! app('config')->get('app.debug')) {
                report($exception);

                return $path;
            } else {
                throw $exception;
            }
        }

        return new HtmlString(app('config')->get('app.mix_url').$manifestDirectory.$manifest[$path]);
    }

    static function theme($name, $path, $manifestDirectory = '')
    {
        return (new static)->__invoke($name, $path, $manifestDirectory);
    }

    static function module($name, $path, $manifestDirectory = '')
    {
        return (new static)->__invoke($name, $path, $manifestDirectory, '/modules');
    }
}
