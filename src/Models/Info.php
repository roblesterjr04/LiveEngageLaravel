<?php

namespace LivePersonInc\LiveEngageLaravel\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    protected $guarded = [];
    protected $appends = [
        'startTime',
    ];

    public function getStartTimeAttribute()
    {
        return new Carbon($this->attributes['startTime']);
    }

    public function getSessionIdAttribute()
    {
        return str_replace($this->accountId, '', $this->engagementId);
    }

    public function getMinutesAttribute()
    {
        return round($this->attributes['duration'] / 60, 2);
    }

    public function getSecondsAttribute()
    {
        return $this->attributes['duration'];
    }

    public function getHoursAttribute()
    {
        return round($this->minutes / 60, 2);
    }
}
