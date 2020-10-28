<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Models\Model;
use Foundry\Core\Repositories\ModelRepository;
use Foundry\Core\Entities\Contracts\IsEntity;
use Foundry\Core\Entities\Contracts\IsFile;
use Foundry\Core\Repositories\Traits\SoftDeleteable;
use Foundry\System\Events\FileCreated;
use Foundry\System\Events\FileDeleted;
use Foundry\System\Events\FileRestored;
use Foundry\System\Events\FileUpdated;
use Foundry\System\Events\FolderCreated;
use Foundry\System\Models\File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class FileRepository
 *
 * @method boolean delete(IsFile | Model | int $model)
 * @method boolean restore(IsFile | Model | int $model)
 * @method IsFile|Model getModel(Model $id)
 *
 * @package Foundry\System\Repositories
 */
class FileRepository extends ModelRepository
{

	use SoftDeleteable;

	protected $dispatchesEvents = [
	    'inserted' => FileCreated::class,
        'updated' => FileUpdated::class,
        'deleted' => FileDeleted::class,
        'restored' => FileRestored::class
    ];

	/**
	 * @return string|Model
	 */
	public function getClassName()
	{
		return File::class;
	}

	public function read($id)
    {
        $file = $this->getModel($id);
        return $file;
    }

	/**
	 * @param IsEntity $entity
	 * @param array $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return \Illuminate\Contracts\Pagination\Paginator
	 */
	public function browse(IsEntity $entity, array $inputs, $page = 1, $perPage = 20)
	{
		return $this->filter(function (Builder $query) use ($entity, $inputs) {

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

			if ($search = Arr::get($inputs, 'search')) {
				$query->where('files.original_name', 'like', "%" . $search . "%");
			}

			$deleted = Arr::get($inputs, 'deleted', 'undeleted');
			if ($deleted == 'deleted') {
				$query->onlyTrashed();
			}

			return $query;

		}, $page, $perPage);
	}

	/**
	 * @param array $data
	 *
     * @param null $user
     * @return bool|Model|mixed
     */
	public function insert($data, $user = null)
	{
		$file = self::make($data);
		$file->token = Str::random(32);

		if ($user) {
            $file->user()->associate($user);
        }

        $result = $file->save();

        if ($parent = Arr::get($data, 'folder')) {

			if ($parent = FolderRepository::repository()->find($parent)) {
				$folder = FolderRepository::repository()->make(['name' => Arr::get($data, 'original_name')]);
				$folder->setFile($file);
				$folder->parent = $parent;
				if ($folder->save()) {
				    event(new FolderCreated($folder));
                }
			}
		}

		if ($result) {
            event(new FileCreated($file));

			return $file;
		} else {
			return false;
		}
	}
}
