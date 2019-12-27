<?php

namespace Foundry\System\Http\Requests\PickLists;

use Foundry\Core\Requests\Response;

class ReadPickListRequest extends PickListRequest
{

	/**
	 * @return String
	 */
	public static function name(): String {
		return 'foundry.system.pick-lists.read';
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

	/**
	 * @return Response
	 */
	public function handle() : Response
    {
        return Response::success($this->getEntity());
    }


}
