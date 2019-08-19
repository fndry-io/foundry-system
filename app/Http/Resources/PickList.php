<?php

namespace Foundry\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PickList extends JsonResource {

	public function toArray( $request ) {
		return $this->resource->toArray();
	}
}