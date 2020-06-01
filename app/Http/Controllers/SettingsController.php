<?php


namespace Foundry\System\Http\Controllers;


use Foundry\Core\Repositories\SettingRepository;
use Foundry\Core\Requests\Response;
use Foundry\System\Http\Requests\Settings\BrowseSettingsRequest;
use Foundry\System\Http\Requests\Settings\EditSettingRequest;
use Foundry\System\Http\Resources\Setting;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Inputs\Setting\SettingInput;
use Foundry\System\Services\SettingService;

class SettingsController extends Controller
{

    public function browse(BrowseSettingsRequest $request, SettingService $service)
    {
        $inputs = SearchFilterInput::make($request->all());
        $inputs->validate();

        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);

        return $service
            ->browse($inputs, $page, $limit )
            ->asResource(Setting::class, true)
            ->toJsonResponse($request)
            ;
    }

    public function edit(EditSettingRequest $request, SettingRepository $repository, SettingService $service)
    {
        $setting = $repository->find($request->route('_entity'));
        $inputs = new SettingInput($setting, $request->all());

        if ($view = $inputs->viewOrValidate($request)) {
            return Response::success($view)->toJsonResponse($request);
        }

        return $service->edit($inputs, $request->getEntity())->toJsonResponse($request);
    }

}
