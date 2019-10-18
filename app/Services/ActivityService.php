<?php

namespace Foundry\System\Services;

use Foundry\Core\Entities\Contracts\IsEntity;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\System\Repositories\ActivityRepository;

class ActivityService extends BaseService
{
    /**
     * Browse the list of companies
     *
     * @param IsEntity $entity
     * @param Inputs $inputs
     * @param int $page
     * @param int $perPage
     *
     * @return Response The data key will contain an instance of Paginator
     * @see Paginator
     */
    public function browse(IsEntity $entity, Inputs $inputs, $page = 1, $perPage = 20): Response
    {
        return Response::success(ActivityRepository::repository()->browse($entity, $inputs->values(), $page, $perPage));
    }

}
