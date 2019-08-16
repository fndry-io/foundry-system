<?php

namespace Foundry\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class User
 * @package Foundry\System\Http\Resources
 */
class User extends JsonResource {

	public function toArray( $request ) {
		return [
			'id' => $this->id,
			'username' => $this->username,
			'display_name' => $this->display_name,
			'email' => $this->email,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}