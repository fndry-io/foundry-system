<?php

namespace Foundry\System\Events;

use Foundry\Core\Entities\Contracts\IsFolder;

class FolderRestored
{
    public $folder;

    public function __construct(IsFolder $folder)
    {
        $this->folder = $folder;
    }

}
