<?php

namespace Foundry\System\Entities\Contracts;

use Foundry\Core\Entities\Contracts\IsEntity;
use Foundry\System\Models\Activity;

interface IsActivity extends IsEntity
{

    public function activitable();

    public function node();

    /**
     * @param IsActivitable $model
     * @param $event
     * @param $eventText
     * @return Activity|bool
     */
    static public function fromActivitable(IsActivitable $model, $event, $eventText);
}
