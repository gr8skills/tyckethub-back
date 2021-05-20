<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artiste extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
//    protected $appends = [
//        'banner',
//        'thumb'
//    ];

//    protected function getBannerAttribute()
//    {
//        $path = $this->images()->where('tag', Image::IMAGE_TYPES[0])->first('image_url')->image_url ?? '';
//        return config('app.url') . '/' . $path;
//    }
//
//    protected function getThumbAttribute()
//    {
//        $path = $this->images()->where('tag', Image::IMAGE_TYPES[1])->first('image_url')->image_url ?? '';
//        return config('app.url') . '/' . $path;
//    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'artiste_event_pivot', 'artiste_id', 'event_id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
