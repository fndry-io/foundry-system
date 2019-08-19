<?php

namespace Foundry\System\Services;

use Doctrine\ORM\QueryBuilder;
use Foundry\Core\Entities\Contracts\HasReference;
use Foundry\Core\Entities\Contracts\IsReferenceable;
use Foundry\Core\Entities\Contracts\IsSoftDeletable;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\Core\Services\Traits\HasRepository;
use Foundry\System\Entities\File;
use Foundry\System\Entities\Folder;
use Foundry\System\Entities\User;
use Foundry\System\Inputs\File\FileInput;
use Foundry\System\Inputs\Folder\FolderInput;
use Foundry\System\Inputs\SearchFilterInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use LaravelDoctrine\ORM\Facades\EntityManager;

class FileService extends BaseService {

	use HasRepository;

	public function __construct() {
		$this->setRepository(EntityManager::getRepository(File::class));
	}

	/**
	 * Browse for files associated with an entity
	 *
	 * @param HasReference $entity
	 * @param SearchFilterInput $input
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Response
	 */
	public function browse( HasReference $entity, SearchFilterInput $input, $page = 1, $perPage = 20 ): Response {

		$result = $this->getRepository()->filter(function(QueryBuilder $qb) use ($entity, $input) {

			$qb
				->addSelect([
					'file.type',
					'file.original_name as name',
					'file.uuid',
					'file.id',
					'file.size',
					'file.created_at',
					'file.updated_at',
					'file.updated_at',
				])
				->addOrderBy('file.name', 'ASC');

			$where = $qb->expr()->andX();

			$where->add($qb->expr()->eq('file.reference_type', ':reference_type'));
			$where->add($qb->expr()->eq('file.reference_id', ':reference_id'));
			$qb->setParameter(':reference_type', get_class($entity));
			$qb->setParameter(':reference_id', $entity->getId());

			if ($search = $input->input('search')) {
				$where->add($qb->expr()->orX(
					$qb->expr()->like('file.original_name', ':search')
				));
				$qb->setParameter(':search', "%" . $search . "%");
			}

			$where->add($qb->expr()->isNull('file.deleted_at'));

			$qb->where($where);

			return $qb;

		}, $page, $perPage);

		return Response::success($result);
	}

	/**
	 * @param FileInput $input
	 *
	 * @return Response
	 */
	public function add(FileInput $input) : Response
	{
		$values = $input->inputs();

		$visibility = 'private';

		if ($input->input('is_public',  false)) {
			$visibility = 'public';
		}

		$file = $input->getFile()->store($visibility);
		Storage::setVisibility($file, $visibility);

		$values['name'] = $file;
		$values['original_name'] = $input->getFile()->getClientOriginalName();

		$file = new File($values);

		$this->repository->save($file);

		if ($parent = $input->input('folder')) {
			if ($parent = EntityManager::getRepository(Folder::class)->find($parent)) {
				$folder = new Folder();
				$folder->setFile($file);
				$folder->setParent($parent);
				EntityManager::persist($folder);
				EntityManager::flush();
			}
		}

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