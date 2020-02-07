<?php

namespace Foundry\System\Services;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Repositories\SettingRepository;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\System\Inputs\Setting\SettingInput;

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

    public function edit(SettingInput $inputs, $setting): Response
    {
        $setting = SettingRepository::repository()->update($setting, ['value' => $inputs->value('value')]);

        if ($setting) {
            return Response::success($setting);
        } else {
            return Response::error(__('Unable to edit setting'), 500);
        }
    }
}
