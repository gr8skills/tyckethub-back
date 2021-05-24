<?php

namespace App\Models;

use App\Transformers\EventTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    const DISPLAY_TIME_ON = 1;
    const DISPLAY_TIME_OFF = 0;
    const IS_FAVORITE_TRUE = 1;
    const IS_FAVORITE_FALSE = 0;
    const IS_PUBLISHED_ON = 1;
    const IS_PUBLISHED_OFF = 0;
    const IS_PURCHASED_TRUE = 1;
    const IS_PURCHASED_FALSE = 0;
    const IS_COMPLETED_TRUE = 1;
    const IS_COMPLETED_OFF = 0;
    const VISIBILITY_PUBLIC = 1;
    const VISIBILITY_PRIVATE = 0;
    const PUBLISH_OPTION_NOW = 1;
    const PUBLISH_OPTION_SCHEDULE = 0;

    protected $guarded = ['id'];
    protected $appends = [
        'banner',
        'thumb',
        'mobile_image',
        'status'
    ];

//    public $transformer = EventTransformer::class;

    protected function getBannerAttribute()
    {
        $bannerImageObj = $this->images()->where('tag', Image::IMAGE_TYPES[0])->first('image_url');
        if ($bannerImageObj) {
            return config('app.url') . '/' . $bannerImageObj->image_url ?? '';
        }
        return config('app.url') . '/' .  '';

    }

    protected function getThumbAttribute()
    {
        $thumbImageObj = $this->images()->where('tag', Image::IMAGE_TYPES[1])->first('image_url');
        if ($thumbImageObj) {
            return config('app.url') . '/' . $thumbImageObj->image_url ?? '';
        } else{
            return config('app.url') . '/' . '';
        }

    }

    protected function getMobileImageAttribute()
    {
        $mobileImageObj = $this->images()->where('tag', Image::IMAGE_TYPES[2])->first('image_url');
        if ($mobileImageObj) {
            return config('app.url') . '/' . $mobileImageObj->image_url ?? '';

        }else{
            return config('app.url') . '/' . '';
        }
    }

    protected function getStatusAttribute()
    {
        return $this->status()->first()->name;
    }

    public function artistes()
    {
        return $this->belongsToMany(Artiste::class, 'artiste_event_pivot', 'event_id', 'artiste_id');
    }

    public function categories()
    {
        return $this->belongsToMany(EventCategory::class, 'event_category_pivot','event_id', 'event_category_id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function location()
    {
        return $this->hasOne(EventLocation::class, 'event_id');
    }

    public function status()
    {
        return $this->belongsTo(EventStatus::class, 'event_status_id');
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'user_id');
    }

    public function attendees()
    {
        return $this->belongsToMany(Attendee::class, 'attendee_event_pivot', 'event_id', 'user_id')
            ->withPivot(['is_favorite', 'is_purchased']);
    }

    public function tags()
    {
        return $this->belongsToMany(EventTag::class, 'event_tag_pivot', 'event_id', 'event_tag_id');
    }

    public function tickets()
    {
        return $this->hasMany(EventTicket::class, 'event_id');
    }

    public static function generateUID()
    {
        return uniqid(random_int(1, 10), true);
    }
}
