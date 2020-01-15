<?php

namespace Foundry\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class User
 *
 * @package Foundry\System\Http\Resources
 */
class UserSimple extends JsonResource {

	public function toArray( $request ) {
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'username' => $this->username,
			'display_name' => $this->display_name,
            'profile_url' => $this->profile_url
		];
	}
}
