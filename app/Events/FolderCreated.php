<?php


namespace Foundry\System\Events;

use Foundry\Core\Entities\Contracts\IsFolder;

class FolderCreated
{
    public $folder;

    public function __construct(IsFolder $folder)
    {
        $this->folder = $folder;
    }

}
