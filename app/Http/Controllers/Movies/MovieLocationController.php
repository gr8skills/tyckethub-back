<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieLocationController extends ApiController
{
    public function index(Movie $movie)
    {
        $location = $movie->location;
        return $this->showOne($location);
    }
}
