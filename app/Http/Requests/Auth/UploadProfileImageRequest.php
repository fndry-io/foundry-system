<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\System\Http\Requests\Files\UploadImageFileRequest;

class UploadProfileImageRequest extends UploadImageFileRequest
{

    public static function name(): String
    {
        return 'foundry.system.auth.profile.upload';
    }

    protected $width = 600;

    protected $height = 600;

    protected $resize = 'fit';

    public function rules()
    {
        $rules = parent::rules();
        array_push($rules, 'dimensions:min_width=600,min_height=600,ratio=1/1');
        return $rules;
    }
}
