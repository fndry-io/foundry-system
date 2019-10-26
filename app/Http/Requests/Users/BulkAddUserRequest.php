<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\Core\Inputs\SimpleInputs;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\User\UserInput;
use Foundry\System\Services\UserService;
use Illuminate\Support\Facades\Auth;

class BulkAddUserRequest extends FormRequest implements ViewableFormRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.users.add.bulk';
	}

	/**
	 * @param $inputs
	 *
	 * @return \Foundry\Core\Inputs\Inputs|UserInput
	 */
	public function makeInput($inputs) {
		return (new SimpleInputs($inputs, InputTypeCollection::fromTypes([
			(new TextInputType('bulk', __('Bulk Users CSV'), true))
				->setMultiline(15)
				->setPlaceholder('"UserName","Display Name","E-mail","Password","Job Title"
"nameofperson","Name of Person","email@domain.com","passw18rd","Sales Manager"
				')

				->setHelp(__('Copy and paste the CSV values making sure the first row is like the following and the other rows follow the same column layout: "UserName","Display Name","E-mail","Password","Job Title". All users will be given access to the system by default.'))
		])));
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return ($this->user() && $this->user()->isSuperAdmin());
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
		$csv = $this->input('bulk');
		$csv = explode("\n", $csv);
		$headers = str_getcsv(array_shift($csv));

		$wanted = [
			"UserName","Display Name","E-mail","Password","Job Title"
		];

		$diff = array_diff($headers, $wanted);

		if ($diff) {
			return Response::error(__('Headers not set correctly. You are missing the following: :headers', ['headers' => implode(',', $headers)]), 408);
		}

		$headers = array_flip($headers);

		$save = [];

		foreach ($csv as $number => $row) {
			$row = str_getcsv($row);
			$values = [
				"username" => $row[$headers["UserName"]],
				"display_name" => $row[$headers["Display Name"]],
				"email" => $row[$headers["E-mail"]],
				"password" => $row[$headers["Password"]],
				"password_confirmation" => $row[$headers["Password"]],
				"job_title" => $row[$headers["Job Title"]]
			];

			$inputs = new UserInput($values);
			if (!$inputs->validate()) {
				return Response::error(__("Row :number has invalid data", ['number' => $number + 2]), 408);
			} else {
				$save[] = $inputs;
			}
		}

		foreach ($save as $input) {
			UserService::service()->add($input);
		}

		return Response::success();
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		$form->setTitle(__('Bulk Import'));
		$form->setButtons((new SubmitButtonType(__('Import'), $form->getAction())));

		$form->addChildren(
			(new SectionType(__('Job Title & Position')))->addChildren(
				RowType::withChildren($form->get('bulk'))
			)
		);

		return $form;
	}
}
