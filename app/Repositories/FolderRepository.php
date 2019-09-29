<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Entities\Contracts\HasFolder;
use Foundry\Core\Entities\Contracts\IsFolder;
use Foundry\Core\Models\Model;
use Foundry\Core\Repositories\ModelRepository;
use Foundry\Core\Entities\Contracts\IsEntity;
use Foundry\System\Models\Folder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * Class FolderRepository
 *
 * @method boolean delete(IsFolder|Model|int $model)
 * @method boolean restore(IsFolder|Model|int $model)
 * @method IsFolder|Model getModel(Model $id)
 *
 * @package Foundry\System\Repositories
 */
class FolderRepository extends ModelRepository {

	/**
	 * @return string|Model
	 */
	public function getClassName()
	{
		return Folder::class;
	}

	/**
	 * @param IsEntity $folder
	 * @param array $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return \Illuminate\Contracts\Pagination\Paginator
	 */
	public function browse(IsEntity $folder, array $inputs, $page = 1, $perPage = 20)
	{
		return $this->filter(function(Builder $query) use ($folder, $inputs) {

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
	}

	/**
	 *
	 *
	 * @param HasFolder $entity
	 * @param string|null $name
	 * @param Folder|null $parent
	 * @param bool|null $create
	 *
	 * @return bool|Folder|null|object
	 * @throws \ReflectionException
	 */
	public function getRootFolderByEntity(HasFolder $entity, string $name = null, Folder $parent = null, bool $create = null)
	{
		$class = get_class($entity);

		$folder = $this->findOneBy(['reference_type' => $class, 'reference_id' => $entity->getKey()]);
		if (!$folder && $create) {
			$folder = new Folder();
			if (empty($name)){
				$name = $entity->getFolderName();
			}
			$folder->name = $name;
			$folder->reference()->associate($entity);
			if ($parent) {
				$folder->parent = $parent;
			}

			$folder->save();

			if ($entity instanceof HasFolder) {
				$entity->setFolder($folder);
			}
		}
		return $folder;
	}

	/**
	 * @param IsFolder|Model|int $id
	 * @param array $data
	 *
	 * @return bool|IsFolder|Model
	 */
	public function update($id, $data)
	{
		$folder = $this->getModel($id);

		$folder->fill($data);

		if ($id = Arr::get($data, 'parent')) {
			if ((!$folder->getParent() || $id !== $folder->getParent()->getKey()) && $parent = FolderRepository::repository()->find($id)) {
				$folder->parent()->associate($parent);
			}
		}

		if ($folder->save()) {
			return $folder;
		} else {
			return false;
		}
	}

}