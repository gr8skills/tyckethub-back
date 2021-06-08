<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\ApiController;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class GenreController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $genre = Genre::all();
        return $this->showAll($genre);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => ['required', 'string'],
            'description' => [],
        ]);

        $genre = Genre::create($validated_data);
        return $this->showOne($genre);
    }

    /**
     * Display the specified resource.
     *
     * @param Genre $genre
     * @return JsonResponse
     */
    public function show(Genre $genre)
    {
        return $this->showOne($genre);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Genre $genre
     * @return Response
     */
    public function edit(Genre $genre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Genre $genre
     * @return JsonResponse
     */
    public function update(Request $request, Genre $genre)
    {
        $genre->fill(Arr::only($request->all(), ['name', 'description']));
        if ($genre->isDirty()) {
            $genre->save();
            return $this->showOne($genre);
        }
        return $this->errorResponse('Event category not updated. None of the event category attribute was modified.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Genre $genre
     * @return JsonResponse
     */
    public function destroy(Genre $genre)
    {
        try {
            $genre->delete();
            return $this->showOne($genre);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
