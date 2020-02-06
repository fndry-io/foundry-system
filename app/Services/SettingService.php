<?php

namespace Foundry\System\Services;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Repositories\SettingRepository;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;

class SettingService extends BaseService
{

	/**
	 * @param Inputs $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Response The data key will contain an instance of Paginator
	 * @see Paginator
	 */
	public function browse(Inputs $inputs, $page = 1, $perPage = 20): Response
	{
		return Response::success(SettingRepository::repository()->browse($inputs->values(), $page, $perPage));
	}

    public function edit($inputs, $setting): Response
    {
      //todo do implement
    }
}
