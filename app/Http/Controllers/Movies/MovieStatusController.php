<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\MovieStatus;
use Illuminate\Http\Request;

class MovieStatusController extends ApiController
{
    public function index(Movie $movie)
    {
        $status = $movie->status;
        return $this->showOne($status);
    }

    public function create(Request $request)
    {
        $movieStatus = MovieStatus::create($request->all());
        $status = MovieStatus::all();
        if ($movieStatus)
            return $this->showAll($status);
        else
            return ['error', 'Could not create movie status'];

    }

    public function destroy($movie)
    {
        $movie = MovieStatus::where('id', $movie)->first();
        try {
            $movie->delete();
            return $this->showOne($movie);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

    }

    public function approve($id)
    {
        $id = (int)$id;
        $movie = Movie::where('id', $id)->first();
        try {
            if ($movie['is_published'] === 0)
                $movie['is_published'] = 1;
            else
                $movie['is_published'] = 0;
            $movie->save();
        }catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

    }
}
