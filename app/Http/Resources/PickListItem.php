<?php

namespace Foundry\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PickListItem extends JsonResource {

	public function toArray( $request ) {
		return $this->resource->toArray();
	}
}