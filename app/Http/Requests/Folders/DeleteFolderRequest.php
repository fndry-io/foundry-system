<?php

namespace Foundry\System\Http\Requests\Folders;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\Response;
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
