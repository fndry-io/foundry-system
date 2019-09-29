<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Models\Model;
use Foundry\Core\Repositories\ModelRepository;
use Foundry\Core\Entities\Contracts\IsEntity;
use Foundry\Core\Entities\Contracts\IsFile;
use Foundry\System\Models\File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * Class FileRepository
 *
 * @method boolean delete(IsFile|Model|int $model)
 * @method boolean restore(IsFile|Model|int $model)
 * @method IsFile|Model findOrAbort(Model $id)
 *
 * @package Foundry\System\Repositories
 */
class FileRepository extends ModelRepository {

	/**
	 * @return string|Model
	 */
	public function getClassName()
	{
		return File::class;
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
		return $this->filter(function(Builder $query) use ($entity, $inputs) {

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

		}, $page, $perPage);
	}

	/**
	 * @param array $data
	 *
	 * @return bool|IsFile|Model|mixed
	 */
	public function insert($data)
	{
		$file = self::make($data);
		$file->save();

		if ($parent = Arr::get($data, 'folder')) {
			if ($parent = FolderRepository::repository()->find($parent)) {
				$folder = FolderRepository::repository()->make([]);
				$folder->setFile($file);
				$folder->setParent($parent);
				$folder->save();
			}
		}

		if ($this->save($file)) {
			return $file;
		} else {
			return false;
		}
	}
}