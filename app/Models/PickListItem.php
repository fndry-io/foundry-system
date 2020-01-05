<?php

namespace Foundry\System\Models;

use Foundry\Core\Entities\Contracts\IsPickListItem;
use Foundry\Core\Models\Model;
use Foundry\Core\Models\Traits\Sluggable;

class PickListItem extends Model implements IsPickListItem
{
	use Sluggable;

	protected $table = 'picklist_items';

	protected $sluggable = 'label';

	protected $slug_field = 'identifier';

	protected $attributes = [
		'is_system' => false,
        'sequence' => 0,
        'status' => true
	];

	protected $fillable = [
		'label',
		'description',
		'sequence',
		'status'
	];

	protected $visible = [
		'id',
		'label',
		'description',
		'identifier',
		'sequence',
		'status',
		'created_at',
		'updated_at'
	];

	protected $casts = [
		'status' => 'boolean',
		'is_system' => 'boolean'
	];

	public function picklist()
	{
		return $this->belongsTo(PickList::class)->withoutGlobalScopes();
	}

	/**
	 * @param int|PickList $picklist
	 */
	public function setPicklistAttribute($picklist)
	{
		if(!$picklist instanceof PickList && !empty($picklist)) {
		 	$picklist = PickList::query()->find($picklist);
		}
		if ($picklist) {
			$this->picklist()->associate($picklist);
		}
	}
}
