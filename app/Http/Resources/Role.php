<?php

namespace Foundry\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Role
 *
 * @package Foundry\System\Http\Resources
 */
class Role extends JsonResource {

	public function toArray( $request ) {
		return [
			'id' => $this->id,
			'name' => $this->name,
            'guard_name' => $this->guard_name
		];
	}
}
