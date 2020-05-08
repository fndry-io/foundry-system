<?php

namespace Foundry\System\Http\Controllers;

use Foundry\Core\Requests\Response;
use Foundry\System\Http\Requests\Users\AddUserRequest;
use Foundry\System\Http\Requests\Users\BrowseUsersRequest;
use Foundry\System\Http\Requests\Users\BulkAddUserRequest;
use Foundry\System\Http\Requests\Users\DeleteUserRequest;
use Foundry\System\Http\Requests\Users\EditUserRequest;
use Foundry\System\Http\Requests\Users\ListUsersRequest;
use Foundry\System\Http\Requests\Users\RegisterUserRequest;
use Foundry\System\Http\Requests\Users\RestoreUserRequest;
use Foundry\System\Http\Resources\User;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Inputs\User\UserEditInput;
use Foundry\System\Inputs\User\UserInput;
use Foundry\System\Inputs\User\UserRegisterInput;
use Foundry\System\Repositories\UserRepository;
use Foundry\System\Services\UserService;

class UsersController extends Controller
{
    public function browse(BrowseUsersRequest $request, UserService $userService)
    {
        $inputs = SearchFilterInput::make($request->all());
        $inputs->validate();

        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);

        return $userService
            ->browse($inputs, $page, $limit)
            ->asResource(User::class, true)
            ->toJsonResponse($request);
    }

    public function add(AddUserRequest $request, UserService $userService)
    {
        $input = new UserInput($request->all());
        $view = $input->viewOrValidate($request);

        if ($view) {
            return Response::success($view)->toJsonResponse($request);
        }

        return $userService->add($input)->toJsonResponse($request);
    }

    public function bulkAdd(BulkAddUserRequest $request, UserService $userService)
    {
        $csv = $request->input('bulk');
        $csv = explode("\n", $csv);
        $headers = str_getcsv(array_shift($csv));

        $wanted = [
            "UserName","Display Name","E-mail","Password","Job Title"
        ];

        $diff = array_diff($headers, $wanted);

        if ($diff) {
            return Response::error(__('Headers not set correctly. You are missing the following: :headers', ['headers' => implode(',', $headers)]), 408);
        }

        $headers = array_flip($headers);

        $save = [];

        foreach ($csv as $number => $row) {
            $row = str_getcsv($row);
            $values = [
                "username" => $row[$headers["UserName"]],
                "display_name" => $row[$headers["Display Name"]],
                "email" => $row[$headers["E-mail"]],
                "password" => $row[$headers["Password"]],
                "password_confirmation" => $row[$headers["Password"]]
            ];

            $inputs = new UserInput($values);
            if (!$inputs->validate()) {
                return Response::error(__("Row :number has invalid data", ['number' => $number + 2]), 408);
            } else {
                $save[] = $inputs;
            }
        }

        foreach ($save as $input) {
            $userService->add($input);
        }

        return Response::success();
    }

    public function delete(DeleteUserRequest $request, UserService $userService)
    {
        $user = $request->getEntity();

        if (!$user->trashed() || $request->value('force', false)) {
            return $userService->delete($user)->toJsonResponse($request);
        }

        return Response::success();
    }

    public function edit(EditUserRequest $request, UserService $userService)
    {
        $input = UserEditInput::make($request->all(), null, $request->getEntity());
        $view = $input->viewOrValidate($request);

        if ($view) {
            return Response::success($view)->toJsonResponse($request);
        }

        return $userService->edit($input, $request->getEntity())->toJsonResponse($request);
    }

    public function list(ListUsersRequest $request, UserRepository $repository)
    {
        $q = $request->input('q', '');

        if (strlen($q) < 3) {
            return Response::error(__('Search query must be greater than 3 characters'), 422);
        }

        $results = $repository->getLabelList($q);
        return Response::success($results);
    }

    public function register(RegisterUserRequest $request, UserService $userService)
    {
        $input = UserRegisterInput::make($request->all());
        $view = $input->viewOrValidate($request);

        if ($view) {
            return Response::success($view)->toJsonResponse($request);
        }

        return $userService->register($input)->toJsonResponse($request);
    }

    public function restore(RestoreUserRequest $request, UserService $userService)
    {
        return $userService->restore($request->getEntity())->toJsonResponse($request);
    }
}
