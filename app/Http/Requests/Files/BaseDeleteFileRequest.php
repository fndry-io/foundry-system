<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\System\Repositories\FileRepository;

abstract class BaseDeleteFileRequest extends FileRequest {

	public function authorize() {
        return ($this->user() && (
            (
                $this->getEntity()->user &&
                get_class($this->getEntity()->user) === get_class($this->user()) &&
                $this->getEntity()->user->id === $this->user()->id)
            ) ||
            $this->user()->can('delete files')
        );
	}

    public function findEntity( $id ) {
        return FileRepository::repository()->query()->withTrashed()->where('id', $id)->where('token', $this->input('token'))->first();
    }

    public function rules()
    {
        return [
            'token' => 'required'
        ];
    }

}
