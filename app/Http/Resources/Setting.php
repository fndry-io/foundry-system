<?php

namespace Foundry\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Setting
 *
 * @package Foundry\System\Http\Resources
 */
class Setting extends JsonResource {

	public function toArray( $request ) {
		return [
			'id' => $this->id,
			'name' => $this->name
		];
	}
}
