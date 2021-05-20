<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventLocation extends Model
{
    use HasFactory;

    const PLATFORM_LIVE = 1;
    const PLATFORM_ONLINE = 2;
    const PLATFORM_TO_BE_ANNOUNCED = 3;

    protected $guarded = ['id'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function onlinePlatforms()
    {
        return $this->hasMany(OnlinePlatform::class, 'event_location_id');
    }

    public function onlinePlatformExtra()
    {
        return $this->hasOne(OnlinePlatformExtra::class, 'event_location_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
