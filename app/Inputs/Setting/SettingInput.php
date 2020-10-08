<?php

namespace Foundry\System\Inputs\Setting;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Traits\ViewableInput;
use Foundry\Core\Inputs\Types\CheckboxInputType;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\NumberInputType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Requests\Contracts\ViewableInputInterface;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Models\Setting;
use Foundry\System\Inputs\Setting\Types\DefaultValue;
use Illuminate\Http\Request;

/**
 * Class SettingInput
 *
 * @package Foundry\System\Inputs
 *
 */
class SettingInput extends Inputs implements ViewableInputInterface {

    use ViewableInput;

    /**
     * @var $setting Setting
     */
    private $setting;

    /**
     * @param $setting
     */
    public function setSetting($setting)
    {
        $this->setting = $setting;
    }

    public function __construct($setting, $values = null, $types = null) {
        $this->setSetting($setting);
        parent::__construct($values, $types);
    }

	public function types() : InputTypeCollection
	{
        $model = $this->setting;
        $setting = $this->setting->setting();
	    $valueInput = $this->getValueInput($setting, $model);

		return InputTypeCollection::fromTypes([
            DefaultValue::input()
                        ->setHelp()
                        ->setDisabled(true),
            $valueInput
		]);
	}

	private function getValueInput($setting, $model)
    {

        if ($setting['input']) {
            $class = $setting['input'];
            return $class::input()->setName('value');
        }

        if (isset($setting['options']) && sizeof($setting['options']) > 0) {
            $value = new ChoiceInputType('value', __('New Value'), true, $setting['options'], false);
        } else {
            switch ($model->type) {
                case 'int':
                case 'integer':
                case 'double':
                    $value = new NumberInputType('value', __('New Value'), false);
                    $value->setHelp($setting? $setting['description']: '');
                    break;
                case 'bool':
                case 'boolean':
                    $value = new CheckboxInputType('value', __('Enable/Disable'),false);
                    break;
                case 'string':
                default:
                    $value = new TextInputType('value', __('New Value'), false);
                    $value->setHelp($setting? $setting['description']: '');
                    break;
            }
        }

        if (isset($setting['rules'])) {
            $value->setRules($setting['rules']);
        }

        return $value;
    }

    /**
     * Make a viewable DocType for the request
     *
     * @param Request $request
     * @return FormType
     * @throws \Exception
     */
    public function view(Request $request) : FormType
    {
        $form = $this->form($request);

        $form->setTitle(__('Update Setting'));
        $form->setButtons((new SubmitButtonType(__('Update'), $form->getAction())));

        $form->addChildren(
            (new SectionType(__('Setting')))->addChildren(
                RowType::withChildren($form->get('default')),
                RowType::withChildren($form->get('value'))
            )
        );

        return $form;
    }

}
