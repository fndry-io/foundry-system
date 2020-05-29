<?php

namespace Foundry\System\Http\Controllers;

use Foundry\Core\Requests\Response;
use Foundry\System\Http\Requests\Auth\EditUserRequest;
use Foundry\System\Http\Requests\Auth\ForgotPasswordRequest;
use Foundry\System\Http\Requests\Auth\LoginRequest;
use Foundry\System\Http\Requests\Auth\LogoutRequest;
use Foundry\System\Http\Requests\Auth\ReadUserRequest;
use Foundry\System\Http\Requests\Auth\ResetPasswordRequest;
use Foundry\System\Http\Requests\Auth\SyncUserSettingsRequest;
use Foundry\System\Http\Requests\Auth\UploadProfileImageRequest;
use Foundry\System\Http\Requests\Files\DeleteFileRequest;
use Foundry\System\Http\Resources\AuthUser;
use Foundry\System\Inputs\User\ForgotPasswordInput;
use Foundry\System\Inputs\User\ResetPasswordInput;
use Foundry\System\Inputs\User\UserEditInput;
use Foundry\System\Inputs\User\UserLoginInput;
use Foundry\System\Inputs\User\UserLogoutInput;
use Foundry\System\Services\ImageService;
use Foundry\System\Services\UserService;
use Illuminate\Auth\AuthManager;

class AuthController extends Controller
{
    public function edit(EditUserRequest $request, UserService $service)
    {
        $input = new UserEditInput($request->all());
        $view = $input->viewOrValidate($request);

        if ($view) {
            return Response::success($view)->toJsonResponse($request);
        }

        $entity = $input->getEntity();
        return $service->edit($input, $entity)->asResource(AuthUser::class)->toJsonResponse($request);
    }

    public function forgotPassword(ForgotPasswordRequest $request, UserService $service)
    {
        $input = new ForgotPasswordInput($request->all());
        $view = $input->viewOrValidate($request);

        if ($view) {
            return Response::success($view)->toJsonResponse($request);
        }

        return $service->forgotPassword($input)->toJsonResponse($request);
    }

    public function login(LoginRequest $request, UserService $service)
    {
        $input = new UserLoginInput($request->all());
        $view = $input->viewOrValidate($request);

        if ($view) {
            return Response::success($view)->toJsonResponse($request);
        }

        return $service->login($input)->toJsonResponse($request);
    }

    public function logout(LogoutRequest $request, UserService $service)
    {
        $input = new UserLogoutInput($request->all());
        $input->validate();
        return $service->logout()->toJsonResponse($request);
    }

    public function readUser(ReadUserRequest $request, AuthManager $auth, UserService $service)
    {
        return $service->returnGuardUser($auth->guard(), false)->toJsonResponse($request);
    }

    public function resetPassword(ResetPasswordRequest $request, UserService $service)
    {
        $input = new ResetPasswordInput($request->all());
        $view = $input->viewOrValidate($request);

        if ($view) {
            return Response::success($view)->toJsonResponse($request);
        }

        return $service->resetPassword($input)->toJsonResponse($request);
    }

    public function syncUserSettings(SyncUserSettingsRequest $request, UserService $service)
    {
        $settings = $request->get('settings');

        return $service->syncSettings($this->user(), $settings)->toJsonResponse($request);
    }

    /**
     * Save a file to the system
     *
     * @param UploadProfileImageRequest $request
     * @param ImageService $service
     * @return JsonResponse
     */
    public function savePhoto(UploadProfileImageRequest $request, ImageService $service)
    {
        $values = $request->all();
        return $service->add($request->makeInput($values), $request->user())->toJsonResponse($request);
    }

    /**
     * Delete a profile image from the system
     *
     * @param DeleteFileRequest $request
     * @param ImageService $service
     * @return Response
     * @throws \Exception
     */
    public function deletePhoto(DeleteFileRequest $request, ImageService $service)
    {
        $file = $request->getEntity();
        return $service->delete($file, (boolean) $request->input('force', false));
    }
}
