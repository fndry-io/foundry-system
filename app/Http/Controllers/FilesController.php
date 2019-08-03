<?php

namespace Foundry\System\Http\Controllers;

use Foundry\System\Entities\File;
use Foundry\System\Http\Requests\Files\ViewFileRequest;
use Illuminate\Support\Facades\Storage;

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

}