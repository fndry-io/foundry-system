<?php

namespace Foundry\System\Services;

use Foundry\Core\Entities\Contracts\IsEntity;
use Foundry\Core\Entities\Contracts\IsFolder;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Models\Model;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\System\Inputs\Folder\FolderEditInput;
use Foundry\System\Inputs\Folder\FolderInput;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Models\Folder;
use Foundry\System\Repositories\FolderRepository;

class FolderService extends BaseService {

	/**
	 * Browse the contents of a folder
	 *
	 * @param IsFolder $folder
	 * @param SearchFilterInput $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Response
	 */
	public function browse( IsFolder $folder, SearchFilterInput $inputs, $page = 1, $perPage = 20 ): Response {

		return Response::success(FolderRepository::repository()->browse($folder, $inputs->values(), $page, $perPage));
	}

	/**
	 * Add a folder
	 *
     * @param FolderInput $inputs
     * @param IsFolder $parent
     * @param IsEntity|null $reference
     *
     * @return Response
     */
	public function add(FolderInput $inputs, IsFolder $parent = null, IsEntity $reference = null) : Response
	{
		if ($folder = FolderRepository::repository()->insert($inputs->values(), $parent, $reference)) {
			return Response::success($folder);
		} else {
			return Response::error(__('Unable to add folder'), 500);
		}
	}

	/**
	 * @param FolderEditInput|Inputs $inputs
	 * @param Folder|Model $folder
	 *
	 * @return Response
	 */
	public function edit(FolderEditInput $inputs, Folder $folder) : Response
	{
		if (FolderRepository::repository()->update($folder, $inputs->values())) {
			return Response::success();
		} else {
			return Response::error(__('Unable to update folder'), 500);
		}
	}


	/**
	 * Delete a folder
	 *
	 * @param IsFolder $folder
	 *
	 * @return Response
	 */
	public function delete(IsFolder $folder): Response
	{
		if (FolderRepository::repository()->delete($folder)) {
			return Response::success();
		} else {
			return Response::error(__('Unable to delete folder'), 500);
		}
	}

	/**
	 * Restore a folder
	 *
	 * @param IsFolder $folder
	 *
	 * @return Response
	 */
	public function restore(IsFolder $folder): Response
	{
		if (FolderRepository::repository()->restore($folder)) {
			return Response::success();
		} else {
			return Response::error(__('Unable to restore folder'), 500);
		}
	}


}
