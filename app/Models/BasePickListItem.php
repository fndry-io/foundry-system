<?php

namespace Foundry\System\Models;

use Foundry\System\Repositories\PickListRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\JoinClause;

abstract class BasePickListItem extends PickListItem
{

    protected static function boot()
    {
        parent::boot();
        /**
         * Associate the Item with the correct pick list
         */
        self::creating(function($model){
            /** @var BasePickListItem $model */
            if (!$model->picklist_id) {
                if ($picklist = self::getPicklist()) {
                    $model->picklist_id = $picklist['id'];
                } else {
                    throw new \Exception(sprintf('Pick List \'%s\' does not exist. Did you run \'foundry:sync picklists\'?', [$model::getOriginalList('label')]));
                }
            }
        });
    }

    /**
     * The pick list this item belongs to
     *
     * @return BelongsTo
     */
    public function picklist()
    {
        return parent::picklist()->where('identifier', static::getOriginalList('identifier'));
    }

    /**
     * The base query object
     *
     * @return Builder
     */
    public static function query()
    {
        $identifier = self::getOriginalList('identifier');
        return parent::query()->select('picklist_items.*')->join('picklists', function(JoinClause $query) use ($identifier) {
            $query
                ->on('picklists.id', '=', 'picklist_items.picklist_id')
                ->where('picklists.identifier', $identifier)
            ;
        });
    }

    /**
     * The original Pick List details
     *
     * This is called to help fetch the correct pick list items and to create the original pick list
     *
     * @return array
     */
    abstract public static function originalList() : array;

    /**
     * The original items for the pick list
     *
     * @return array
     */
    abstract public static function originalItems() : array;

    /**
     * Fetches the original pick list details
     *
     * @param null $key
     * @return array|mixed
     */
    public static function getOriginalList($key = null)
    {
        $original = static::originalList();
        if ($key) {
            return $original[$key];
        }
        return $original;
    }

    /**
     * @return mixed
     */
    public static function getPicklist()
    {
        return PickListRepository::repository()->findPicklist(self::getOriginalList('identifier'));
    }
}
