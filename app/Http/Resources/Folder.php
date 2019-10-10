<?php

namespace Foundry\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Folder extends JsonResource {

	public function toArray( $request ) {
		return [
		    'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'file' => new File($this->whenLoaded('file'))
        ];
	}
}
