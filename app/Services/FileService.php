<?php

namespace Foundry\System\Services;

use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\System\Inputs\File\FileInput;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Models\File;
use Foundry\System\Models\Folder;
use Foundry\System\Repositories\FileRepository;
use Foundry\System\Repositories\FolderRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileService extends BaseService {

	/**
	 * Browse for files associated with an entity
	 *
	 * @param \Illuminate\Database\Eloquent\Model $entity
	 * @param SearchFilterInput $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Response
	 */
	public function browse( Model $entity, SearchFilterInput $inputs, $page = 1, $perPage = 20 ): Response {

		return Response::success(FileRepository::repository()->filter(function(Builder $query) use ($entity, $inputs) {

			$query
				->select([
					'files.type',
					'files.original_name as name',
					'files.uuid',
					'files.id',
					'files.size',
					'files.created_at',
					'files.updated_at',
					'files.updated_at',
				])
				->orderBy('files.name', 'ASC');

			$query->where('reference_type', get_class($entity));
			$query->where('reference_id', $entity->getKey());

			if ($search = $inputs->value('search')) {
				$query->where('files.original_name', 'like', "%" . $search . "%");
			}

			$deleted = $inputs->value('deleted', 'undeleted');
			if ($deleted == 'deleted') {
				$query->onlyTrashed();
			}

			return $query;

		}, $page, $perPage));
	}

	/**
	 * @param FileInput $input
	 *
	 * @return Response
	 */
	public function add(FileInput $input) : Response
	{
		$values = $input->values();

		$visibility = 'private';

		if ($input->value('is_public',  false)) {
			$visibility = 'public';
		}

		$file = $input->getFile()->store($visibility);
		Storage::setVisibility($file, $visibility);

		$values['name'] = $file;
		$values['original_name'] = $input->getFile()->getClientOriginalName();

		$file = new File($values);

		FileRepository::repository()->save($file);

		if ($parent = $input->value('folder')) {
			if ($parent = FolderRepository::repository()->find($parent)) {
				$folder = new Folder();
				$folder->setFile($file);
				$folder->setParent($parent);
				$folder->save();
			}
		}

		return Response::success($file);
	}

	/**
	 * Delete a file
	 *
	 * @param File $file
	 *
	 * @return Response
	 * @throws \Exception
	 */
	public function delete(File $file) : Response
	{
		FileRepository::repository()->delete($file);
		return Response::success();
	}

	/**
	 * Delete a file
	 *
	 * @param File $file
	 *
	 * @return Response
	 * @throws \Exception
	 */
	public function restore(File $file) : Response
	{
		FileRepository::repository()->restore($file);
		return Response::success();
	}


}