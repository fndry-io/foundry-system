<?php

namespace Foundry\System\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface AppScoped
{
    /**
     * @return MorphOne
     */
    public function app_scope();
}
