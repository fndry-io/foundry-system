<?php

namespace Foundry\System\Http\Controllers;

use Foundry\Core\Requests\Response;
use Foundry\System\Http\Requests\PickLists\AddPickListItemRequest;
use Foundry\System\Http\Requests\PickLists\AddPickListRequest;
use Foundry\System\Http\Requests\PickLists\BrowsePickListItemsRequest;
use Foundry\System\Http\Requests\PickLists\BrowsePickListsRequest;
use Foundry\System\Http\Requests\PickLists\EditPickListRequest;
use Foundry\System\Http\Requests\PickLists\ReadPickListRequest;
use Foundry\System\Http\Resources\PickList;
use Foundry\System\Http\Resources\PickListItem;
use Foundry\System\Inputs\PickList\AddPickListInput;
use Foundry\System\Inputs\PickList\AddPickListItemInput;
use Foundry\System\Inputs\PickList\EditPickListInput;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Repositories\PickListItemRepository;
use Foundry\System\Services\PickListItemService;
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
        $inputs = AddPickListInput::make($request->all());
        $view = $inputs->viewOrValidate($request);

        if ($view) {
            return Response::success($view)->toJsonResponse($request);
        }

        return PickListService::service()->add($inputs)->toJsonResponse($request);
    }

    public function edit(EditPickListRequest $request)
    {
        $inputs = EditPickListInput::make($request->all());
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

    public function addItem(AddPickListItemRequest $request)
    {
        $inputs = AddPickListItemInput::make($request->all());
        $inputs->setValue('sequence', 0);
        $inputs->setValue('status', true);
        $inputs->setValue('picklist', $request->getEntity()->getKey());
        $view = $inputs->viewOrValidate($request);

        if ($view) {
            return Response::success($view)->toJsonResponse($request);
        }

        return PickListItemService::service()->add($inputs)->toJsonResponse($request);
    }

    public function browseItem(BrowsePickListItemsRequest $request)
    {
        $inputs = SearchFilterInput::make($request->all());
        $inputs->validate();
        $page = (int) $request->input('page', 1);
        $limit = (int) $request->input('limit', 20);

        return PickListItemService::service()
            ->browse($request->getEntity(), $inputs, $page, $limit)
            ->asResource(PickListItem::class, true)
            ->toJsonResponse($request);
    }

    public function listItems(ReadPickListRequest $request)
    {
        /** @var PickListItemRepository */
        $repo = PickListItemRepository::repository();
        $q = $request->input('q', '');

        if (strlen($q) < 3) {
            return Response::error(__('Search query must be greater than 3 characters'), 422);
        }

        $results = $repo->getLabelList($request->getEntity(), $q);
        return Response::success($results);
    }
}
