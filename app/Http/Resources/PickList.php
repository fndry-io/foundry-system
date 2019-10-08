<?php

namespace Foundry\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PickList extends JsonResource {

	public function toArray( $request ) {
		return [
            'id' => $this->id,
            'label' => $this->label,
            'description' => $this->description,
            'identifier' => $this->identifier,
            'is_tag' => $this->is_tag,
            'default_item' => $this->default_item,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
	}
}
