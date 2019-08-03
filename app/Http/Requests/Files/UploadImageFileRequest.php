<?php

namespace Foundry\System\Http\Requests\Files;

class UploadImageFileRequest extends BaseUploadFileRequest {

	/**
	 * {@inheritdoc}
	 */
	public static function name(): String {
		return 'foundry.system.files.upload.image';
	}

	public function fileTypes() {
		return [
			'jpeg',
			'gif',
			'png'
		];
	}

	public function isPublic() {
		return true;
	}

}
