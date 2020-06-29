<?php

namespace Foundry\System\Services;

use Foundry\Core\Inputs\Types\Contracts\IsFileInput;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\Core\Entities\Contracts\IsEntity;
use Foundry\Core\Entities\Contracts\IsFile;
use Foundry\System\Inputs\File\FileInput;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Models\File;
use Foundry\System\Models\Folder;
use Foundry\System\Repositories\FileRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Storage;

class FileService extends BaseService
{

    public FileRepository $repository;

    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

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

		return Response::success($this->repository->browse($entity, $inputs->values(), $page, $perPage));
	}

	/**
     * Add a file to the system
     *
     * @param FileInput|IsFileInput $input
     * @param Authenticatable $user
     * @return Response
     */
	public function add(IsFileInput $input, Authenticatable $user): Response
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

		$file = $this->repository->insert($values, $user);

        if ($file) {
            //create a folder to represent this file in the system_folders
            if ($folder_id = $input->value('folder')) {
                $folder = new Folder();
                $folder->name = $values['original_name'];
                $folder->parent_id = $folder_id;
                $folder->file()->associate($file);
                $folder->is_file = true;
                $folder->save();
            }

            $data = $file->toArray();
		    $data['token'] = $file->token;
			return Response::success($file);
		} else {
			return Response::error(__('Unable to add file'), 500);
		}
	}

    public function edit(IsFileInput $inputs, File $file) : Response
    {
        if ($file = $this->repository->update($file, $inputs->values())) {
            return Response::success($file);
        } else {
            return Response::error(__('Unable to update file'), 500);
        }
    }

	/**
	 * Delete a file
	 *
     * @param IsFile $file
     * @param bool $force
     * @return Response
     * @throws \Exception
     */
	public function delete(IsFile $file, bool $force = false): Response
	{
		if ($this->repository->delete($file, $force)) {
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
		if ($this->repository->restore($file)) {
			return Response::success();
		} else {
			return Response::error(__('Unable to restore file'), 500);
		}
	}


}
