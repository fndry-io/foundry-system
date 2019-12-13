<?php

namespace Foundry\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class AppScope extends Model
{
    protected $table = 'app_scopes';

    public function scopeable()
    {
        return $this->morphTo();
    }

    static function fromSession()
    {
        return Session::get(config('scope.session_key', 'scope'), null);
    }
}
