<?php

namespace Foundry\System\Http\Requests\Folders;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\System\Models\Folder;
use Foundry\System\Repositories\FolderRepository;
use Foundry\System\Services\FolderService;

class DeleteFolderRequest  extends FolderRequest implements EntityRequestInterface
{

	public static function name(): String {
		return 'foundry.system.folders.delete';
	}

	public function authorize()
	{
        return ($this->user() && $this->user()->can('delete folders'));
	}

    /**
     * @param mixed $id
     *
     * @return null|Folder|object
     */
    public function findEntity($id)
    {
        return FolderRepository::repository()->query()->withTrashed()->find($id);
    }

	/**
	 * Handle the request
	 *
	 * @return Response
	 * @throws \Exception
	 */
	public function handle() : Response
	{
		return FolderService::service()->delete($this->getEntity());
	}

}
