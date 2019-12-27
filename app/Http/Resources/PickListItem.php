<?php

namespace Foundry\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PickListItem extends JsonResource {

	public function toArray( $request ) {
		return [
            'id' => $this->id,
            'label' => $this->label,
            'description' => $this->description,
            'identifier' => $this->identifier,
            'sequence' => $this->sequence,
            'status' => $this->status,
            'is_default' => (isset($this->is_default)) ? (boolean) $this->is_default : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
	}
}
