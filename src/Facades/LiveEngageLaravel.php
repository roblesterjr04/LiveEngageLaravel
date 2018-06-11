<?php

namespace LivePersonInc\LiveEngageLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class LiveEngageLaravel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'live-engage-laravel';
    }
}
