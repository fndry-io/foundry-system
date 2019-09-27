<?php

namespace Foundry\System\Models;

use Foundry\Core\Models\Model;
use Foundry\Core\Models\Traits\Uuidable;
use Kalnoy\Nestedset\NodeTrait;


/**
 * Class Node
 *
 * @package Foundry\System\Models
 */
class Node extends Model {

	use Uuidable;
	use NodeTrait;

	protected $fillable = [];

	protected $visible = [
		'id',
		'uuid'
	];

	public function getLftName()
	{
		return 'lft';
	}

	public function getRgtName()
	{
		return 'rgt';
	}

	public function getParentIdName()
	{
		return 'parent_id';
	}

	/**
	 * @param $value
	 *
	 * @throws \Exception
	 */
	public function setParentAttribute($value)
	{
		$this->setParentIdAttribute($value);
	}

	public function entity()
	{
		return $this->morphTo();
	}

}