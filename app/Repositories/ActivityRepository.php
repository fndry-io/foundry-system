<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Entities\Contracts\HasNode;
use Foundry\Core\Entities\Contracts\IsEntity;
use Foundry\Core\Entities\Contracts\IsNode;
use Foundry\Core\Repositories\ModelRepository;
use Foundry\System\Models\Activity;
use Foundry\System\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ActivityRepository
 *
 * @method static Activity make($values)
 *
 * @package Modules\Foundry\Activities\Repositories
 */
class ActivityRepository extends ModelRepository {

    /**
     * @return Activity|string
     */
    public function getClassName()
    {
        return Activity::class;
    }

    /**
     *
     * @param IsEntity|Model $entity
     * @param array $inputs
     * @param int $page
     * @param int $perPage
     *
     * @return Paginator
     */
    public function browse($entity, array $inputs = [], $page = 1, $perPage = 20)
    {
        return $this->filter(function(Builder $query) use ($entity, $inputs) {
            $query
                ->select('activities.*')
                ->orderBy('activities.created_at', 'DESC')
            ;

            /**
             * We need to determine based on the object we have been given, if we are accessing that specific node tree
             * Or accessing a activitable directly
             */
            $node = null;
            if ($entity instanceof HasNode) {
                $node = $entity->getNode();
            } elseif ($entity instanceof IsNode) {
                $node = $entity;
            } else {
                $query->whereHasMorph('activitable', get_class($entity), function(Builder $query) use ($entity) {
                    $query->where('id', $entity->getKey());
                });
            }

            if ($node) {
                //todo move this to a scope type query
                $subQuery = $node->descendants()->select('id');
                $query
                    ->join('nodes', 'nodes.id', 'activities.node_id')
                    ->where(function(Builder $query) use ($node, $subQuery) {
                        $query
                            ->where('nodes.id', $node->getKey())
                            ->orWhereRaw('`nodes`.`id` IN (' . $subQuery->toSql() . ')', $subQuery->getBindings());
                    })
                ;
            }

            return $query;

        }, $page, $perPage);
    }

    /**
     * @param Model $entity
     * @param string $title
     * @param User|Model|Authenticatable $user
     * @param string|null $description
     * @return bool|Activity
     */
    static function create($entity, $title, $user, $description = null)
    {
        $activity = self::make(['title' => $title, 'description' => $description]);
        $activity->activitable()->associate($entity);

        if ($user) {
            $activity->setCreatedBy($user);
        }

        if ($activity->save()) {
            return $activity;
        } else {
            return false;
        }
    }

}
