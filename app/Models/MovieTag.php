<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieTag extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_tag_pivot', 'movie_tag_id', 'movie_id');
    }
}
