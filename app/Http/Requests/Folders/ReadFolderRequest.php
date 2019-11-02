<?php

namespace Foundry\System\Http\Requests\Folders;

use Foundry\Core\Requests\Response;

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
        return ($this->user() && $this->user()->can('view folders'));
    }

	/**
	 * Handle the request
	 *
	 * @see FolderResource
	 * @return Response
	 */
    public function handle() : Response
    {
	    return Response::success($this->getEntity());
    }

}
