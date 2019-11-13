<?php

namespace Foundry\System\Http\Requests\Files;

class DeleteFileRequest extends BaseDeleteFileRequest {

	/**
	 * {@inheritdoc}
	 */
	public static function name(): String {
		return 'foundry.system.files.delete';
	}



}
