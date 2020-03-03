<?php

namespace Foundry\System\Http\Requests\Files;

class UploadFileRequest extends BaseUploadFileRequest {

	/**
	 * {@inheritdoc}
	 */
	public static function name(): String {
		return 'foundry.system.files.upload';
	}

	static function fileTypes() {
		return [
		    //images
			'jpeg',
            'jpg',
			'gif',
			'png',

            //documents
			'doc',
			'docx',
            'odt',
            'ods',
            'ppt',
            'pptx',
			'pdf',
			'txt',
			'xls',
			'xlsx',
            'rtf',

            //audio/video
            'mp3',
            'wav',
            'mpa',
            'mpeg',
            'ogg',
            'mpg',
            'mpe',
            'mov',
            'mp4',

            //compressed
            'zip',
            'rar',
            'tar',

            //data
            'csv',

            //fonts
            'ttf',
            'otf',
            'fon',
            'fnt',
		];
	}

}
