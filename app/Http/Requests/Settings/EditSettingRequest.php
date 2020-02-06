<?php

namespace Foundry\System\Http\Requests\Settings;

use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\NumberInputType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\Role\RoleInput;
use Foundry\System\Models\Setting;
use Foundry\System\Services\SettingService;

class EditSettingRequest extends SettingRequest implements ViewableFormRequestInterface, EntityRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.settings.edit';
	}

	/**
	 * @param $inputs
	 *
	 * @return string
	 */
	public function makeInput($inputs) {
		return new RoleInput($inputs);
	}

	/**
	 * Determine if the role is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('edit settings'));
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
		return SettingService::service()->edit($this->getInput(), $this->getEntity());
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		$form->setTitle(__('Update Setting'));
		$form->setButtons((new SubmitButtonType(__('Update'), $form->getAction())));

		$model = $form->getEntity();
        $settings = Setting::settings();
        $setting = isset($settings[$model->domain . '.' . $model->name]) ? $settings[$model->domain . '.' . $model->name] : null;

        if (isset($setting['options']) && sizeof($setting['options']) > 0) {
            $value = new ChoiceInputType('value', __('New Value'), true, $setting['options'], false);
        } else {
            switch ($model->type) {
                case 'int':
                case 'integer':
                case 'double':
                    $value = new NumberInputType('value', __('New Value'), false);
                    break;
                case 'bool':
                case 'boolean':
                    $value = new ChoiceInputType('value', __('New Value'), true, [
                        1 => __("Yes"), 0 => __("No")
                    ], false);
                    break;
                case 'string':
                default:
                    $value = new TextInputType('value', __('New Value'), false);
                    break;
            }
        }

        $form->addChildren(
            (new SectionType(__('Setting')))->addChildren(
                RowType::withChildren($form->get('default')),
                RowType::withChildren($value->setHelp($model? $model->description: ''))
            )
        );

		return $form;
	}

}
