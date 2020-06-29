<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\System\Inputs\File\FileInput;
use Foundry\System\Inputs\File\ImageInput;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UploadImageFileRequest extends BaseUploadFileRequest {

    /**
     * @var null|int The with the set the image to
     */
    protected $width = null;

    /**
     * @var null|null The height to set the image to
     */
    protected $height = null;

    /**
     * Controls the resize of the image.
     *
     * Available options:
     *  - `crop`: crop the image using the supplied width and height
     *  - `resize`: resize the image to the desired width and height (does not maintain aspect ratio)
     *  - `fit`: resize and crop the image to the best possible position using the supplied with and height
     *  - null: do not resize the image at all
     *
     * @var null|string The resize mode to use.
     */
    protected $resize = null;

    /**
     * The max file size to allow
     *
     * @return int
     */
    static function fileSize()
    {
        return 2000;
    }

	static function fileTypes() {
		return [
			'jpeg',
			'gif',
			'png'
		];
	}

    public function isPublic() {
		return true;
	}

    /**
     * The file input to use to validate the uploaded file
     *
     * Override this function to customise the file upload with your own max size and file types
     *
     * @param $inputs
     *
     * @see FileInput::fromUploadedFile
     * @return ImageInput
     */
    public function makeInput( $inputs ) {
        if (empty($this->file)) {
            throw new BadRequestHttpException('No file supplied');
        }
        $input = ImageInput::fromUploadedFile($this->file, $inputs, $this->isPublic(), $this->width, $this->height, $this->resize);

        if (!$input->value('folder') && ($folder = $this->folder())) {
            $input->setValue('folder', $folder->getKey());
        }

        return $input;
    }

}
