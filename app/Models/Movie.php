<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
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
//        'banner',
        'thumb',
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


    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genre_pivot','movie_id', 'genre_id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function location()
    {
        return $this->hasOne(MovieLocation::class, 'movie_id');
    }

    public function status()
    {
        return $this->belongsTo(MovieStatus::class, 'movie_status_id');
    }


    public function attendees()
    {
        return $this->belongsToMany(Attendee::class, 'attendee_movie_pivot', 'movie_id', 'user_id')
            ->withPivot(['is_favorite', 'is_purchased']);
    }


    public function tags()
    {
        return $this->belongsToMany(MovieTag::class, 'movie_tag_pivot', 'movie_id', 'movie_tag_id');
    }

    public function tickets()
    {
        return $this->hasMany(MovieTicket::class, 'movie_id');
    }

    public static function generateUID()
    {
        return uniqid(random_int(1, 10), true);
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'user_id');
    }
}
