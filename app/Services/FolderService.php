<?php

namespace Foundry\System\Services;

use Doctrine\ORM\QueryBuilder;
use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\Core\Services\Traits\HasRepository;
use Foundry\System\Entities\Folder;
use Foundry\System\Inputs\Folder\FolderEditInput;
use Foundry\System\Inputs\Folder\FolderInput;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Repositories\FolderRepository;
use LaravelDoctrine\ORM\Facades\EntityManager;

class FolderService extends BaseService {

	use HasRepository;

	/**
	 * @var FolderRepository
	 */
	protected $repository;

	public function __construct() {
		$this->setRepository(EntityManager::getRepository(Folder::class));
	}

	/**
	 * Browse the contents of a folder
	 *
	 * @param Folder $folder
	 * @param SearchFilterInput $input
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Response
	 */
	public function browse( Folder $folder, SearchFilterInput $input, $page = 1, $perPage = 20 ): Response {

		$result = $this->getRepository()->filter(function(QueryBuilder $qb) use ($folder, $input) {

			$qb
				->addSelect([
					'folder.id',
					'folder.name',
					'folder.created_at',
					'folder.updated_at',
					'folder.is_file',
					'file.type as file_type',
					'file.original_name as file_name',
					'file.uuid as file_uuid',
					'file.id as file_id',
					'file.size as file_size',
					'file.created_at as file_created_at',
					'file.updated_at as file_updated_at',
				])
				->leftJoin('folder.file', 'file')
				->orderBy('folder.is_file', 'ASC')
				->orderBy('folder.name', 'ASC');

			$where = $qb->expr()->andX();

			$where->add($qb->expr()->eq('folder.parent', ':parent'));
			$qb->setParameter(':parent', $folder->getId());

			if ($search = $input->input('search')) {
				$where->add($qb->expr()->orX(
					$qb->expr()->like('folder.name', ':search')
				));
				$qb->setParameter(':search', "%" . $search . "%");
			}

			$where->add($qb->expr()->isNull('folder.deleted_at'));
			$where->add($qb->expr()->isNull('file.deleted_at'));

			$qb->where($where);

			return $qb;

		}, $page, $perPage);

		return Response::success($result);
	}

	/**
	 * Read the contents of a folder
	 *
	 * @param $id
	 *
	 * @return null|object
	 */
	public function read($id){
		return $this->repository->find($id);
	}

	/**
	 * Add a folder
	 *
	 * @param FolderInput $input
	 * @param bool $flush
	 *
	 * @return Response
	 */
	public function add(FolderInput $input, $flush = true) : Response
	{
		$folder = new Folder($input->inputs());

		if ($parent = $input->input('parent')) {
			if ($parent = $this->repository->find($parent)) {
				$folder->setParent($parent);
			}
		}

		if (($ref_type = $input->input('reference_type')) && ($ref_id = $input->input('reference_id'))) {
			/** @var HasIdentity $ref */
			if ($ref = EntityManager::getRepository($ref_type)->find($ref_id)) {
				$folder->attachReference($ref);
			}
		}

		if ($parent) {
			$this->repository->persist($folder);
		} else {
			$this->repository->getTreeRepository()->persistAsFirstChild($folder);
		}

		if ($flush) $this->repository->flush($folder);
		return Response::success($folder);
	}

	/**
	 * @param FolderEditInput|Inputs $input
	 * @param Folder|Entity $folder
	 *
	 * @return Response
	 */
	public function edit(FolderEditInput $input, Folder $folder) : Response
	{
		$folder->fill($input);

		if ($id = $input->input('parent')) {
			if ((!$folder->getParent() || $id !== $folder->getParent()->getId()) && $parent = $this->repository->find($id)) {
				$folder->setParent($parent);
			}
		}

		$this->repository->save($folder);
		return Response::success($folder);
	}

	/**
	 * Delete a folder
	 *
	 * @param Folder|Entity $folder
	 *
	 * @return Response
	 */
	public function delete(Folder $folder) : Response
	{
		if ($folder->isDeleted() && $files = $folder->getFiles()) {
			foreach ($files as $file) {
				FileService::service()->delete($file, true, false);
			}
		}
		$this->repository->delete($folder);
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
		$this->repository->restore($folder);
		return Response::success();
	}


}