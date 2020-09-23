<?php

namespace Foundry\System\Http\Requests\Files;

class UploadFileRequest extends BaseUploadFileRequest {

	static function fileTypes() {
		return [
		    //images
			'jpeg',
            'jpg',
			'gif',
			'png',
            'svg',

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
