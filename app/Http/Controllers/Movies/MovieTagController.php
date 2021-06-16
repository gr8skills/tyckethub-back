<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieTagController extends ApiController
{
    public function index(Movie $movie)
    {
        $tags = $movie->tags;
        return $this->showAll($tags);
    }
}
