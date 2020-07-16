<?php

namespace Foundry\System\Http\Controllers;

use Foundry\Core\Requests\Response;
use Foundry\System\Http\Requests\Files\BrowseFilesRequest;
use Foundry\System\Http\Requests\Files\DeleteFileRequest;
use Foundry\System\Http\Requests\Files\UploadFileRequest;
use Foundry\System\Http\Requests\Files\UploadImageFileRequest;
use Foundry\System\Http\Requests\Files\ViewFileRequest;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Models\File;
use Foundry\System\Services\FileService;
use Foundry\System\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class FilesController extends Controller {


	public function download(ViewFileRequest $request)
	{
		/**
		 * @var File $file
		 */
		$file = $request->getEntity();

		if (config('filesystems.default') === 's3') {
			if (!$file->isPublic()) {
				/**
				 * Create a temp url with an expiry period
				 */
				$url = Storage::temporaryUrl( $file->name, now()->addMinutes(20), [
					'ResponseContentDisposition' => 'attachment; filename="' . str_replace(['"', "'"], "", $file->original_name) . '"'
				]);
			} else {
				$url = Storage::url($file->name);
			}
			return redirect()->to($url);
		}

//		/**
//		 * If the file is on s3 we need to generate a token to allow the user to download the file
//		 */
//		if ($file->filesystem === 's3') {
//			$disk = Storage::disk('s3');
//			$client = $disk->getDriver()->getAdapter()->getClient();
//
//			if ($disk->exists($file->name)) {
//				$command = $client->getCommand('GetObject', [
//					'Bucket'                     => config('filesystems.disks.s3.bucket'),
//					'Key'                        => $file->name,
//					'ResponseContentDisposition' => 'attachment; filename="' . str_replace(['"', "'"], "", $file->original_name) . '"'
//				]);
//				$request = $client->createPresignedRequest($command, '+20 minutes');
//				return redirect()->to($request->getUri());
//			}
//		}

		return Storage::download($file->name, $file->original_name);
	}

	public function read(ViewFileRequest $request)
	{
		/**
		 * @var File $file
		 */
		$file = $request->getEntity();

		if (config('filesystems.default') === 's3') {
			if (!$file->isPublic()) {
				/**
				 * Create a temp url with an expiry period
				 */
				$url = Storage::temporaryUrl( $file->name, now()->addMinutes(20) );
			} else {
				$url = Storage::url($file->name);
			}
			return redirect()->to($url);
		}

		if ($file->isPublic()) {
			return redirect()->to(Storage::url($file->name));
		} else {
			return response()->file(storage_path('app/' . $file->name));
		}
	}

    /**
     * Save a file to the system
     *
     * @param UploadFileRequest $request
     * @param FileService $service
     * @return JsonResponse
     */
	public function saveFile(UploadFileRequest $request, FileService $service)
    {
        $user = Auth::user();
        return $service->add($request->makeInput($request->all()), $user)->toJsonResponse($request);
    }

    /**
     * Save a file to the system
     *
     * @param UploadImageFileRequest $request
     * @param ImageService $service
     * @return JsonResponse
     */
    public function saveImage(UploadImageFileRequest $request, ImageService $service)
    {
        $user = Auth::user();
        return $service->add($request->makeInput($request->all()), $user)->toJsonResponse($request);
    }

    /**
     * Delete a file from the system
     *
     * @param DeleteFileRequest $request
     * @param FileService $service
     * @return Response
     * @throws \Exception
     */
    public function delete(DeleteFileRequest $request, FileService $service)
    {
        $file = $request->getEntity();
        return $service->delete($file, (boolean) $request->input('force', false));
    }

    /**
     * @param BrowseFilesRequest $request
     * @param FileService $service
     * @return JsonResponse
     * @throws ValidationException
     */
    public function browse(BrowseFilesRequest $request, FileService $service)
    {
        $inputs = SearchFilterInput::make($request->all());

        $inputs->validate();

        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);

        return $service
            ->browse($inputs, $page, $limit)
            //todo add File resource
            ->toJsonResponse($request);
    }

}
