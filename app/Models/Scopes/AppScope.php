<?php

namespace Foundry\System\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Session;

class AppScope implements Scope
{
    /**
     * All of the extensions to be added to the builder.
     *
     * @var array
     */
    protected $extensions = ['IgnoreAppScope'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, \Illuminate\Database\Eloquent\Model $model)
    {
        if ($scope = Session::get(config('scope.session_key', 'scope'))) {
            $builder->whereNotNull($model->getQualifiedAppScopeColumn())->where($model->getQualifiedAppScopeColumn(), '=', $scope->getKey());
        }
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    /**
     * Get the "scoped" column for the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return string
     */
    protected function getAppScopeColumn(Builder $builder)
    {
        if (count((array) $builder->getQuery()->joins) > 0) {
            return $builder->getModel()->getQualifiedAppScopeColumn();
        }

        return $builder->getModel()->getAppScopeColumn();
    }

    /**
     * Add the ignore-app-scoped extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addIgnoreAppScope(Builder $builder)
    {
        $builder->macro('ignoreAppScope', function (Builder $builder) {

            $builder->withoutGlobalScope($this);

            return $builder;
        });
    }

}
