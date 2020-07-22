<?php

namespace Foundry\System\Testing;

trait SeedsFoundrySystem
{

    public function seedFoundrySystem()
    {
        $this->seed(\FoundrySystemSeeder::class);
    }
}
