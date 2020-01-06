<?php

namespace Foundry\System\Models;

use Foundry\Core\Entities\Contracts\IsPickList;
use Foundry\Core\Models\Model;
use Foundry\Core\Models\Traits\Sluggable;

class PickList extends Model implements IsPickList
{
    const SLUGGABLE_COLUMN  = 'identifier';
    const SLUGGABLE_SOURCE  = 'label';

	use Sluggable;

	protected $table = 'picklists';

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
