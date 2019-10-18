<?php

namespace Foundry\System\Events;

use Foundry\System\Models\User;
use Illuminate\Queue\SerializesModels;

class UserLoggedOut
{
    use SerializesModels;

    public $user;

    /**
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
