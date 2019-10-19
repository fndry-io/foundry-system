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
		return !!($this->user());
	}

    /**
     * @param mixed $id
     *
     * @return null|Folder|object
     */
    public function findEntity($id)
    {
        return FolderRepository::repository()->findOneBy(['id' => $id]);
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
