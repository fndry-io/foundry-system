<?php

namespace Foundry\System\Models;

use Foundry\Core\Entities\Contracts\HasNode;
use Foundry\Core\Models\Model;
use Foundry\Core\Models\Traits\Blameable;
use Foundry\Core\Models\Traits\NodeReferenceable;
use Foundry\System\Entities\Contracts\IsActivitable;
use Foundry\System\Entities\Contracts\IsActivity;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model implements IsActivity, HasNode {

    const UPDATED_AT = null;

    use NodeReferenceable;
    use Blameable;

    protected $table = 'activities';

    protected $dates = [
        'created_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:sO',
    ];

    protected $fillable = [
        'title',
        'description'
    ];

    protected $visible = [
        'id',
        'title',
        'description',
        'created_by',
        'created_at'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function(IsActivity $model){
            if ($model->activitable) {
                if ($model->activitable instanceof HasNode) {
                    $model->node()->associate($model->activitable->getNode());
                }
            }
        });
    }

    /**
     * @param Model|IsActivitable $model
     * @param $event
     * @param $eventText
     * @return bool|Activity
     */
    static public function fromActivitable(IsActivitable $model, $event, $eventText)
    {
        $replacements = $model->getActivityReplacements([]);
        $activity = new Activity(['title' => $model->getActivityTitle($event, $replacements, $eventText), 'description' => $model->getActivityDetail($replacements)]);
        $activity->activitable()->associate($model);
        if ($activity->save()) {
            return $activity;
        } else {
            return false;
        }
    }

    /**
     * @param $model
     * @param $title
     * @param null $description
     * @return bool|Activity
     */
    static public function make($model, $title, $description = null)
    {
        $activity = new Activity(['title' => $title, 'description' => $description]);
        $activity->activitable()->associate($model);
        if ($activity->save()) {
            return $activity;
        } else {
            return false;
        }
    }

    /**
     * @return MorphTo
     */
    public function activitable()
    {
        return $this->morphTo()->withoutGlobalScopes();
    }

}
