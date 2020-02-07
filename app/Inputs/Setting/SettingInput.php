<?php

namespace Foundry\System\Inputs\Setting;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\CheckboxInputType;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\NumberInputType;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Models\Setting;
use Foundry\System\Inputs\Setting\Types\DefaultValue;

/**
 * Class SettingInput
 *
 * @package Foundry\System\Inputs
 *
 */
class SettingInput extends Inputs {

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

        $settings = Setting::settings();
        $setting = isset($settings[$model->domain . '.' . $model->name]) ? $settings[$model->domain . '.' . $model->name] : null;

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

        return $value;
    }

}
