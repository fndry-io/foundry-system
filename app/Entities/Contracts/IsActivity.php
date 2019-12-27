<?php

namespace Foundry\System\Entities\Contracts;

use Foundry\Core\Entities\Contracts\IsEntity;
use Foundry\System\Models\Activity;

interface IsActivity extends IsEntity
{

    public function activitable();

    public function node();
}
