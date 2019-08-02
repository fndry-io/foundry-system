<?php

namespace Foundry\System\Http\Requests\PickLists;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\System\Services\PickListService;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Modules\Foundry\Checklists\Services\ChecklistService;

class ReadPickListRequest extends PickListRequest
{

	public static function name(): String {
		return 'picklists.picklists.entity';
	}

	/**
	 * Determine if the board is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return !!($this->user());
	}


	public function handle() : Response
    {
        return Response::success($this->getEntity());
    }


}
