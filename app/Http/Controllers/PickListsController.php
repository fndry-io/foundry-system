<?php

namespace Foundry\System\Http\Controllers;

use Foundry\Core\Requests\Response;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Http\Requests\PickLists\AddPickListRequest;
use Foundry\System\Http\Requests\PickLists\BrowsePickListsRequest;
use Foundry\System\Http\Requests\PickLists\EditPickListRequest;
use Foundry\System\Http\Requests\PickLists\ReadPickListRequest;
use Foundry\System\Http\Resources\PickList;
use Foundry\System\Inputs\PickList\PickListInput;
use Foundry\System\Services\PickListService;

class PickListsController extends Controller
{
    public function browse(BrowsePickListsRequest $request)
    {
        $inputs = SearchFilterInput::make($request->all());
        $inputs->validate();

        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);

        return PickListService::service()
            ->browse($inputs, $page, $limit)
            ->asResource(PickList::class, true)
            ->toJsonResponse($request);
    }

    public function add(AddPickListRequest $request)
    {
        $inputs = PickListInput::make($request->all());
        $view = $inputs->viewOrValidate($request);

        if ($view) {
            return Response::success($view)->toJsonResponse($request);
        }

        return PickListService::service()->add($inputs)->toJsonResponse($request);
    }

    public function edit(EditPickListRequest $request)
    {
        $inputs = PickListInput::make($request->all());
        $view = $inputs->viewOrValidate($request);

        if ($view) {
            return Response::success($view)->toJsonResponse($request);
        }

        return PickListService::service()->edit($inputs, $request->getEntity())->toJsonResponse($request);
    }

    public function read(ReadPickListRequest $request)
    {
        return Response::success($request->getEntity())->toJsonResponse($request);
    }
}
