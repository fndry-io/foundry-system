<?php

namespace Foundry\System\Http\Requests\Entities;
use Foundry\Core\Requests\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Class UserRegisterEntity
 *
 * @package Foundry\System\Http\Requests\Entities
 *
 * @property $first_name
 * @property $last_name
 * @property $email
 * @property $password
 * @property $super_admin
 */
class UserRegisterEntity {

	protected $attributes = [];

	protected $fillable = [
		'first_name',
		'last_name',
		'email',
		'password'
	];

	public function __construct($inputs) {
		$this->fill($inputs);
	}

	public function rules() {
		return [
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required|email',
			'password' => 'required',
		];
	}

	public function validate()
	{
		$validator = Validator::make($this->attributes, $this->rules());
		if ($validator->fails()) {
			return Response::error(__('Error validating request'), 422, $validator->errors());
		}
		return Response::success();
	}

	public function fill($inputs)
	{
		$this->attributes = array_merge($this->attributes, $inputs);
	}

	public function toArray()
	{
		return $this->attributes;
	}

	public function __set( $name, $value ) {
		$this->attributes[$name] = $value;
	}

	public function __get( $name ) {
		if (isset($this->attributes[$name])) {
			return $this->attributes[$name];
		} else {
			return null;
		}
	}

}