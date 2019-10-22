<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\System\Inputs\Types\Image;

class ProfileImage extends Image implements Field {

	/**
	 * @return $this
	 */
	static function input( ): Inputable {
		return parent::input()
            ->setRequired(false)
            ->setName('profile_image')
            ->setLabel('Profile Image')
            ->setHelp(__('Select an image that is at least 600px X 600px and with a ratio of 1:1.'))
            ->setAction(resourceUri('foundry.system.auth.profile.upload'))
            ;
	}

}
