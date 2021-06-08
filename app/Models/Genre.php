<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

//    protected $table = 'genres';

    protected $guarded = ['id'];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_genre_pivot', 'movie_genre_id', 'movie_id');
    }


}
