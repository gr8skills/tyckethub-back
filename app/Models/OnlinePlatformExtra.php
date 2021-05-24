<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlinePlatformExtra extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function location()
    {
        return $this->belongsTo(EventLocation::class, 'event_location_id');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
