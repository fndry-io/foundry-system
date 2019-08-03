<?php

namespace Foundry\System\Http\Requests\Files;

class UploadFileRequest extends BaseUploadFileRequest {

	/**
	 * {@inheritdoc}
	 */
	public static function name(): String {
		return 'foundry.system.files.upload';
	}

}