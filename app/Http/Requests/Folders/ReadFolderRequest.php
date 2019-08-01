<?php

namespace Foundry\System\Http\Requests\Folders;

use Foundry\Core\Requests\Response;
use Foundry\System\Services\FolderService;

class ReadFolderRequest extends FolderRequest
{
	public static function name(): String {
		return 'foundry.system.folders.read';
	}

	/**
     * Determine if the Folder is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	//todo update to use the permissions
	    return !!($this->user());
    }

	/**
	 * Handle the request
	 *
	 * @see FolderResource
	 * @return Response
	 */
    public function handle() : Response
    {
	    return FolderService::service()->withContents($this->getEntity());
    }

}
