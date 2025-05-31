<?php

namespace Isoftd\DynamicPermissions\App\Policies;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Http\Requests\NovaRequest;

class HasNovaResource
{
    /**
     * @param NovaRequest $request
     * @param Builder<Model> $query
     * @return Builder<Model>
     */
    public function checkOwnership(NovaRequest $request, Builder $query): Builder
    {
        return $query;
    }
}
