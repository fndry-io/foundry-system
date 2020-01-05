<?php

namespace Foundry\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PickListItemBasic extends JsonResource {

	public function toArray( $request ) {
		return [
            'id' => $this->id,
            'label' => $this->label,
            'identifier' => $this->identifier
        ];
	}
}
