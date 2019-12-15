<?php

namespace Foundry\System\Models;

use Foundry\Core\Models\Model;
use Foundry\Core\Models\Traits\Sluggable;

class Account extends Model
{
    use Sluggable;

	protected $table = 'account';
}
