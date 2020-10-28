<?php


namespace Foundry\System\Events;


use Foundry\System\Models\File;

abstract class FileEvent
{
    public File $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }
}
