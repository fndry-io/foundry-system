<?php

namespace Foundry\System\Listeners;

use Foundry\Core\Seeders\SettingSeeder;
use Foundry\System\Models\Setting;

class SyncSettings{

    use SettingSeeder;

    public function handle()
    {
        $this->seed();
    }

    /**
     * @inheritDoc
     */
    protected function settings(): array
    {
       return Setting::settings();
    }

    protected function model(): string
    {
       return Setting::class;
    }
}
