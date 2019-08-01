<?php

namespace Foundry\System\Services;

use Foundry\Core\Entities\Contracts\IsReferenceable;
use Foundry\Core\Entities\Contracts\IsSoftDeletable;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\Core\Services\Traits\HasRepository;
use Foundry\System\Entities\File;
use Foundry\System\Entities\Folder;
use Foundry\System\Inputs\File\FileInput;
use Illuminate\Support\Facades\Storage;
use LaravelDoctrine\ORM\Facades\EntityManager;

class FileService extends BaseService {

	use HasRepository;

	public function __construct() {
		$this->setRepository(EntityManager::getRepository(File::class));
	}

	/**
	 * @param FileInput $input
	 *
	 * @return Response
	 */
	public function add(FileInput $input) : Response
	{
		$values = $input->inputs();
		$values['name'] = $input->getFile()->store('files');
		$values['original_name'] = $input->getFile()->getClientOriginalName();

		$file = new File($values);

		if ($folder = $input->input('folder')) {
			if ($folder = EntityManager::getRepository(Folder::class)->find($folder)) {
				$file->folder = $folder;
			}
		}

		$this->repository->save($file);
		return Response::success($file);
	}

	/**
	 * Delete a file
	 *
	 * @param File $file
	 * @param bool $force
	 * @param bool $flush
	 *
	 * @return Response
	 */
	public function delete(File $file, $force = false, $flush = true) : Response
	{
		$this->repository->delete($file, false);
		if ($file instanceof IsSoftDeletable && $file->isDeleted() || $force) {
			Storage::delete($file->name);
		}
		if ($flush) $this->repository->flush();
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
		$this->repository->restore($file);
		return Response::success();
	}

}