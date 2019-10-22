<?php

namespace Foundry\System\Services;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\Contracts\IsFileInput;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\Core\Entities\Contracts\IsEntity;
use Foundry\Core\Entities\Contracts\IsFile;
use Foundry\System\Inputs\File\FileInput;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Repositories\FileRepository;
use Illuminate\Support\Facades\Storage;

class FileService extends BaseService
{

	/**
	 * Browse for files associated with an entity
	 *
	 * @param IsEntity $entity
	 * @param SearchFilterInput $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Response
	 */
	public function browse(IsEntity $entity, SearchFilterInput $inputs, $page = 1, $perPage = 20): Response
	{

		return Response::success(FileRepository::repository()->browse($entity, $inputs->values(), $page, $perPage));
	}

	/**
	 * @param FileInput|Inputs $input
	 *
	 * @return Response
	 */
	public function add(Inputs $input): Response
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

		$file = FileRepository::repository()->insert($values);
		if ($file) {
			return Response::success($file);
		} else {
			return Response::error(__('Unable to add file'), 500);
		}
	}

	/**
	 * Delete a file
	 *
	 * @param IsFile $file
	 *
	 * @return Response
	 */
	public function delete(IsFile $file): Response
	{
		if (FileRepository::repository()->delete($file)) {
			return Response::success();
		} else {
			return Response::error(__('Unable to delete file'), 500);
		}
	}

	/**
	 * Restore a file
	 *
	 * @param IsFile $file
	 *
	 * @return Response
	 */
	public function restore(IsFile $file): Response
	{
		if (FileRepository::repository()->restore($file)) {
			return Response::success();
		} else {
			return Response::error(__('Unable to restore file'), 500);
		}
	}


}
