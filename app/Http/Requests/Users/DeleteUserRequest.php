<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\Core\Inputs\SimpleInputs;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Models\User;
use Foundry\System\Repositories\UserRepository;
use Foundry\System\Services\UserService;

class DeleteUserRequest extends UserRequest implements InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.users.delete';
	}

	/**
	 * Make the input class for the request
	 *
	 * @param $inputs
	 *
	 * @return mixed
	 */
	public function makeInput($inputs)
	{
		return (new SimpleInputs($inputs, InputTypeCollection::fromTypes([
			(new ChoiceInputType('force', 'Force', false))->setDefault(false),
		])));
	}

	/**
	 * @param mixed $id
	 *
	 * @return null|User|object
	 */
	public function findEntity($id)
	{
		return UserRepository::repository()->query()->withTrashed()->find($id);
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('delete users'));
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
		/**
		 * @var User $user
		 */
		$user = $this->getEntity();
		if (!$user->trashed() || $this->getInput()->value('force', false)) {
			return UserService::service()->delete($user);
		}
		return Response::success();
	}


}
