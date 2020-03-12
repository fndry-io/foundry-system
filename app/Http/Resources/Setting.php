<?php

namespace Foundry\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Setting
 *
 * @package Foundry\System\Http\Resources
 */
class Setting extends JsonResource {

	public function toArray( $request )
    {
        /** @var \Foundry\Core\Models\Setting $model */
        $model = $this->resource->model;
	    $settings = $model::settings();

	    if (!isset($settings[$this->domain . '.' . $this->name])) {
	        throw new \Exception(sprintf('Base settings for %s not found in %s', $this->domain . '.' . $this->name, $model));
        }

	    $setting = $settings[$this->domain . '.' . $this->name];
	    $value = $this->value;
	    $default = $setting['default'];

        settype( $default, isset( $setting['type'] ) ? $setting['type'] : 'string' );

        if($value !== null)
            settype( $value, isset( $setting['type'] ) ? $setting['type'] : 'string' );

        if($setting['type'] === 'bool' || $setting['type'] === 'boolean'){
            $default = $default? 'Yes': "No";
            if($value !== null)
                $value = $value? 'Yes': "No";
        }

	    if($setting){
            return [
                'id' => $this->id,
                'name' => $this->name,
                'default' => $default,
                'label' => $setting['label'],
                'description' => $setting['description'],
                'value' => $value
            ];
        }

	    return [];

	}
}
