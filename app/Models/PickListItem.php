<?php

namespace Foundry\System\Models;

use Foundry\Core\Entities\Contracts\IsPickListItem;
use Foundry\Core\Models\Model;
use Foundry\Core\Models\Traits\Sluggable;

class PickListItem extends Model implements IsPickListItem
{
    const SLUGGABLE_COLUMN  = 'identifier';
    const SLUGGABLE_SOURCE  = 'label';

	use Sluggable;

	protected $table = 'picklist_items';

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

    /**
     * Get models with given slug
     *
     * @param $slug
     *
     * @return mixed
     */
    private function getRelatedSlugs( $slug ) {
        if (method_exists($this, 'trashed')) {
            $query = static::withTrashed();
        } else {
            $query = static::query();
        }
        $query->select( $this->getQualifiedSluggableColumn(), $this->getQualifiedSluggableSourceColumn() )
            ->where( $this->getQualifiedSluggableColumn(), 'like', $slug . '%' )
            ->where( 'picklist_id', $this->picklist_id)
        ;

        //ensure not the same record
        if ( $key = $this->getKey() ) {
            $query->where( $this->getQualifiedKeyName(), '!=', $key );
        }

        return $query->get();
    }
}
