<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\System\Http\Requests\Files\DeleteFileRequest;

class DeleteProfileImageRequest extends DeleteFileRequest
{

    public static function name(): String
    {
        return 'foundry.system.auth.profile.delete';
    }

    /**
     * {@inheritdoc}
     */
    public function authorize() {
        return ($this->user() && $this->user()->profile_image->id === $this->getEntity()->id);
    }

}
