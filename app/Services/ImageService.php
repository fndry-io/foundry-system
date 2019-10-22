<?php

namespace Foundry\System\Services;

use Foundry\Core\Auth\TokenGuard;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\Contracts\IsFileInput;
use Foundry\Core\Requests\Response;
use Foundry\System\Inputs\File\ImageInput;
use Foundry\System\Repositories\FileRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService extends FileService
{

	/**
	 * @param ImageInput|Inputs $input
	 *
	 * @return Response
	 */
	public function add(Inputs $input): Response
	{
		$values = $input->values();

		$visibility = 'private';

		if ($input->value('is_public',  false)) {
			$visibility = 'public';
		}

		$file = $input->getFile();
		$image = Image::make($file);

		switch ($input->value('resize')) {
            case 'crop':
                $image->crop($input->value('width'), $input->value('height'));
                break;
            case 'resize':
                $image->resize($input->value('width'), $input->value('height'));
                break;
            case 'fit':
                $image->fit($input->value('width'), $input->value('height'));
                break;
        }

        $filename = $file->hashName($visibility);

        if (!Storage::put($filename, $image->stream())) {
            return Response::error(__('Unable to save the file'), 500);
        }

		Storage::setVisibility($filename, $visibility);

		$values['name'] = $filename;
		$values['original_name'] = $input->getFile()->getClientOriginalName();

		$file = FileRepository::repository()->insert($values);
		if ($file) {

		    $data = $file->toArray();

		    if (!$file->isPublic()) {
		        $params = ['_entity' => $file->getKey()];
                $guard = Auth::guard();
		        if ($guard && $guard instanceof TokenGuard && $user = $guard->user()) {
                    $params['api_token'] = $user->api_token;
                }
		        $url = route('files.read', $params);
            } else {
                $url = Storage::url($file->name);
            }
            $data['url'] = url($url);

			return Response::success($data);
		} else {
			return Response::error(__('Unable to add file'), 500);
		}
	}

}
