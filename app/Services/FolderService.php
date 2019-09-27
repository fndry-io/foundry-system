<?php

namespace Foundry\System\Services;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Models\Model;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\System\Inputs\Folder\FolderEditInput;
use Foundry\System\Inputs\Folder\FolderInput;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Models\Folder;
use Foundry\System\Repositories\FolderRepository;
use Illuminate\Database\Eloquent\Builder;

class FolderService extends BaseService {

	/**
	 * Browse the contents of a folder
	 *
	 * @param Folder $folder
	 * @param SearchFilterInput $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Response
	 */
	public function browse( Folder $folder, SearchFilterInput $inputs, $page = 1, $perPage = 20 ): Response {

		$result = FolderRepository::repository()->filter(function(Builder $query) use ($folder, $inputs) {

			$query
				->select([
					'folders.id',
					'folders.name',
					'folders.created_at',
					'folders.updated_at',
					'folders.is_file',
					'files.type as file_type',
					'files.original_name as file_name',
					'files.uuid as file_uuid',
					'files.id as file_id',
					'files.size as file_size',
					'files.created_at as file_created_at',
					'files.updated_at as file_updated_at',
				])
				->leftJoin('files', 'folders.file_id', '=', 'files.id')
				->orderBy('folders.is_file', 'ASC')
				->orderBy('folders.name', 'ASC');

			$query->where('folders.parent_id', $folder->getKey());

			if ($search = $inputs->value('search')) {
				$query->where('folder.name', 'like', "%" . $search . "%");
			}

			$deleted = $inputs->value('deleted', 'undeleted');
			if ($deleted == 'deleted') {
				$query->onlyTrashed();
			}
			return $query;

		}, $page, $perPage);

		return Response::success($result);
	}

	/**
	 * Add a folder
	 *
	 * @param FolderInput $inputs
	 * @param Model|null $reference
	 *
	 * @return Response
	 */
	public function add(FolderInput $inputs, Model $reference = null) : Response
	{
		$folder = new Folder($inputs->values());

		if ($reference) {
			$folder->setReference($reference);
		}

		if ($parent = $inputs->value('parent')) {
			if ($parent = FolderRepository::repository()->find($parent)) {
				$folder->setParent($parent->getKey());
			}
		}

		if (!$parent) {
			$folder->makeRoot();
		}
		$folder->save();

		return Response::success($folder);
	}

	/**
	 * @param FolderEditInput|Inputs $inputs
	 * @param Folder|Model $folder
	 *
	 * @return Response
	 */
	public function edit(FolderEditInput $inputs, Folder $folder) : Response
	{
		$folder->fill($inputs->values());

		if ($id = $inputs->value('parent')) {
			if ((!$folder->getParent() || $id !== $folder->getParent()->getKey()) && $parent = FolderRepository::repository()->find($id)) {
				$folder->parent()->associate($parent);
			}
		}

		FolderRepository::repository()->save($folder);
		return Response::success($folder);
	}

	/**
	 * Delete a folder
	 *
	 * @param Folder|Model $folder
	 *
	 * @return Response
	 * @throws \Exception
	 */
	public function delete(Folder $folder) : Response
	{
		FolderRepository::repository()->delete($folder);
		return Response::success();
	}

	/**
	 * Restore a folder
	 *
	 * @param Folder $folder
	 *
	 * @return Response
	 * @throws \Exception
	 */
	public function restore(Folder $folder) : Response
	{
		FolderRepository::repository()->restore($folder);
		return Response::success();
	}


}