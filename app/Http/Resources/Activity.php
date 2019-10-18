<?php

namespace Foundry\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Activity extends JsonResource {

	public function toArray( $request ) {

		return [
		    'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'related_type' => $this->related_type,
            'related_id' => $this->related_id
        ];
	}
}
