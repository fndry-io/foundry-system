<?php

namespace Foundry\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class File extends JsonResource {

	public function toArray( $request ) {
		return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'original_name' => $this->original_name,
            'url' => $this->url,
            'type' => $this->type,
            'alt' => $this->alt,
            'size' => $this->size,
            'folder' => new Folder($this->whenLoaded('folder')),
            'is_public' => $this->is_public,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at
        ];
	}
}
