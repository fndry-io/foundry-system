<?php

namespace Foundry\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AuthUser
 *
 * @package Foundry\System\Http\Resources
 */
class AuthUser extends JsonResource {

	public function toArray( $request ) {
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'username' => $this->username,
			'display_name' => $this->display_name,
			'email' => $this->email,
			'active' => $this->active,
			'super_admin' => $this->super_admin,
			'timezone' => $this->timezone,
            'is_super_admin' => $this->isSuperAdmin(),
            'is_admin' => $this->isAdmin(),
			'settings' => $this->settings,
            'profile_url' => $this->profile_url,
            'abilities' => $this->getAllPermissions()->pluck('name')
		];
	}
}
