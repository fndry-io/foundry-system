<?php

namespace Foundry\System\Model\Traits;

use Foundry\System\Models\AppScope as AppScopeModel;
use Foundry\System\Models\Scopes\AppScope;
use Illuminate\Support\Facades\Session;

/**
 * Trait AppScopeable
 *
 * @package Foundry\Core\Traits
 */
trait AppScopeable
{
    /**
     * Boot the app scoped trait for a model.
     *
     * @return void
     */
    public static function bootAppScopeable()
    {
        static::addGlobalScope(new AppScope());

        static::creating(function($model){
            if ($scope = Session::get(config('scope.session_key', 'scope'))) {
                $model->setAppScope($scope);
            }
        });
    }

    /**
     * @param AppScopeModel $app_scope
     */
    public function setAppScope( AppScopeModel $app_scope ): void {
        $this->{$this->getAppScopeColumn()} = $app_scope->getKey();
    }

    /**
     * Get the name of the "deleted at" column.
     *
     * @return string
     */
    public function getAppScopeColumn()
    {
        return defined('static::APP_SCOPE') ? static::APP_SCOPE : 'app_scope_id';
    }

    /**
     * Get the fully qualified "namespaced at" column.
     *
     * @return string
     */
    public function getQualifiedAppScopeColumn()
    {
        return $this->qualifyColumn($this->getAppScopeColumn());
    }

    /**
     * @return AppScopeModel
     */
    public function app_scope() {
        return $this->belongsTo(AppScopeModel::class, $this->getAppScopeColumn());
    }

}
