<?php

namespace Modules\ModuleRelease2\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasRelationable
{
    /**
     * Apply eager loading if relations are provided.
     */
    protected function withRelations(Builder $query, array $relations = []): Builder
    {
        return !empty($relations) ? $query->with($relations) : $query;
    }
}