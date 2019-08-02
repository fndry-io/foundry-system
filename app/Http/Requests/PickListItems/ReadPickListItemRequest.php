<?php

namespace Foundry\System\Http\Requests\PickListItems;


use Foundry\Core\Requests\Response;


class ReadPickListItemRequest extends PickListItemRequest
{

	public static function name(): String {
		return 'picklistitems.picklistitems.entity';
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
