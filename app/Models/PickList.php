<?php

namespace Foundry\System\Models;

use Foundry\Core\Entities\Contracts\IsPickList;
use Foundry\Core\Models\Model;
use Foundry\Core\Models\Traits\Sluggable;

class PickList extends Model implements IsPickList
{
	use Sluggable;

	protected $table = 'picklists';

	protected $sluggable = 'label';

	protected $slug_field = 'identifier';

	protected $attributes = [
		'is_system' => false
	];

	protected $fillable = [
		'label',
		'description',
		'is_tag'
	];

	protected $visible = [
		'id',
		'label',
		'description',
		'identifier',
		'is_tag',
		'default_item',
		'created_at',
		'updated_at'
	];

	protected $casts = [
		'is_tag' => 'boolean'
	];

	public function items()
	{
		return $this->hasMany(PickListItem::class, 'picklist_id')->withoutGlobalScopes();
	}
}
