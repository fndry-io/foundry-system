<?php

namespace Foundry\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class User
 * @package Foundry\System\Http\Resources
 */
class RoleResource extends JsonResource {

	public function toArray( $request ) {
		return [
			'id' => $this->id,
			'name' => $this->name
		];
	}
}