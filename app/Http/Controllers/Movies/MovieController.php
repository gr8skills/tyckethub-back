<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Models\EventLocation;
use App\Models\EventTag;
use App\Models\Image;
use App\Models\Movie;
use App\Models\MovieLocation;
use App\Models\MovieTag;
use App\Traits\ImageHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovieController extends ApiController
{
    use ImageHelper;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'update', 'destroy']]);
    }

    public function index()
    {
        $data = Movie::with(['images'])
            ->where('is_published', 1)
            ->orderBy('id', 'DESC')->limit(15)->paginate();
        $movies = $data->map(function ($dat) {
            $temp = [];
            $temp['id'] = $dat->id;
            $temp['title'] = $dat->name;
            $temp['status'] = $dat->status;
            $temp['image_url'] = $dat->thumb;
            $temp['date'] = $dat->start_date;
            return $temp;
        });
        return ($data);
    }


    public function store(StoreMovieRequest $request)
    {
        if ($request->validated()) {
            $validated_data = $request->all();
            $validated_data['start_date'] = Carbon::parse($validated_data['start_date']);
            $validated_data['end_date'] = Carbon::parse($validated_data['end_date']);
            $validated_data['start_time'] = substr($validated_data['start_time'], 0, 8);
            $validated_data['end_time'] = substr($validated_data['end_time'], 0, 8);
            $validated_data['movie_status_id'] = 1;
            $validated_data['is_published'] = 1;
            $validated_data['uid'] = Movie::generateUID();
            try {
                $movie = Movie::create($validated_data);
                if ($movie) {
                    if (!is_null($validated_data['genre_ids']) && count($validated_data['genre_ids']) > 0) {
                        foreach ($validated_data['genre_ids'] as $genre_id) {
                            $movie->genres()->attach($genre_id);
                        }
                        $save_genre_id = $validated_data['genre_ids'][0];
                        $movie->update([
                            'genre_id' => $save_genre_id,
                            ]);
                    }
                    if (!is_null($request['artiste_ids']) && count($request['artiste_ids']) > 0) {
                        foreach ($request['artiste_ids'] as $artiste_id) {
                            $movie->artistes()->attach($artiste_id);
                        }
                    }
                    if ($request->has('tags') && count($request->input('tags')) > 0) {
                        $available_tag_names = EventTag::all(['id', 'name'])->pluck('name', 'id')->toArray();

                        foreach ($request->input('tags') as $tag) {
                            $tag = strtolower($tag);

                            if (in_array($tag, $available_tag_names)) {
                                $tag_id = array_search($tag, $available_tag_names);
                                $movie->tags()->attach($tag_id);
                            } else {
                                $tag_new = EventTag::create([
                                    'name' => $tag,
                                ]);
                                $movie->tags()->attach($tag_new->id);
                            }
                        }
                    }
                    if ($request->location) {
                        $movie->location()->create([
                            'country_id' => $request->location['country'],
                            'state_id' => $request->location['state'],
                            'city_name' => $request->location['city'],
                            'venue_address' => $request->location['address'],
                            'platform' => $this->mapInputPlatformToInteger($request->location['platform'])
                        ]);
                    }
//                    if ($request->has('images') && count($request->file('images')) > 0) {
//                        $uploaded_images = $request->file('images');
//                        foreach ($uploaded_images as $image) {
//                            if (array_search($image, $uploaded_images) === 'banner') {
//                                $image_path = $this->storeImage($image);
//                            } else {
//                                $image_path = $this->storeImage($image, \App\Models\Image::IMAGE_TYPES[1]);
//                            }
//
//                            if ($image_path) {
//                                $event->images()->create([
//                                    'image_url' => $image_path
//                                ]);
//                            }
//                        }
//                    }
                }
                $createdMovie = Movie::with([
                    'genres',
                    'images',
                    'location',
                    'location.country',
                    'location.state',
                    'status',
                    'tickets',
                    'tickets.setting'
                ])->findOrFail($movie->id);
                return $this->showOne($createdMovie);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage());
            }
        }
    }

    public function show($movie)
    {
        $movie = Movie::with([
            'genres',
            'images',
            'location',
            'location.country',
            'location.state',
            'location.onlinePlatforms',
            'status',
            'tickets',
            'tickets.setting'
        ])->findOrFail($movie);
        return $this->showOne($movie);
    }

    public function update(Request $request, Movie $movie)
    {
        $error = true;
        $data = $request->all();

        $movie->fill($data);
        if ($movie->isDirty()) {
            DB::transaction(function () use (&$movie, &$data, &$error) {
                $movie->save();
                $this->saveMovieData($movie, $data, 'update');
                $error = false;
                return $error;
            });

            if (!$error) {
                return $this->showOne($movie);
            }
            return $this->errorResponse('Update failed. Please try again later');
        }
        return $this->errorResponse('No change was made to the movie details');
    }

    public function destroy(Movie $movie)
    {
        try {
            $movie->delete();
            $movie->location()->delete();

            return $this->showOne($movie);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    private function saveMovieData($movie, $payload, $action = 'store')
    {
        DB::transaction(function () use (&$movie, &$payload, &$action) {
            if (!is_null($payload['genre_ids']) && isset($payload['genre_ids']) > 0) {
                foreach ($payload['genre_ids'] as $genre_id) {
                    if ($action === 'update') {
                        $movie->genres->each(function ($gen) use (&$movie) {
                            $movie->genres()->detach($gen->id);
                        });
                    }
                    $movie->genres()->attach($genre_id);
                }
            }

//            dump('Artistes id payload ', $payload['artiste_ids']);
//            if (!is_null($payload['artiste_ids']) && isset($payload['artiste_ids']) > 0) {
//                foreach ($payload['artiste_ids'] as $artiste_id) {
//                    if ($action === 'update') {
//                        $movie->artistes->each(function ($art) use (&$movie) {
//                            $movie->artistes()->detach($art->id);
//                        });
//                    }
//                    $movie->artistes()->attach($artiste_id);
//                }
//            }
//
//            if (!is_null($payload['tags']) && count($payload['tags']) > 0) {
//                $available_tag_names = MovieTag::all(['id', 'name'])->pluck('name', 'id')->toArray();
//
//                foreach ($payload['tags'] as $tag) {
//                    $tag = strtolower($tag);
//
//                    if (in_array($tag, $available_tag_names)) {
//                        $tag_id = array_search($tag, $available_tag_names);
//
//                        if ($action === 'update') {
//                            $movie->tags()->detach($tag_id);
//                        }
//
//                        $movie->tags()->attach($tag_id);
//                    } else {
//                        $tag_new = MovieTag::create([
//                            'name' => $tag,
//                        ]);
//                        $movie->tags()->attach($tag_new->id);
//                    }
//                }
//            }

//            if (!is_null($payload['images']) && count($payload['images']) > 0) {
//                $uploaded_images = $payload['images'];
//                foreach ($uploaded_images as $image) {
//                    if (array_search($image, $uploaded_images) === 'banner') {
//                        $image_path = $this->storeImage($image);
//                    } else {
//                        $image_path = $this->storeImage($image, \App\Models\Image::IMAGE_TYPES[1]);
//                    }
//
//                    if ($image_path) {
//                        $movie->images()->create([
//                            'image_url' => $image_path
//                        ]);
//                    }
//                }
//            }
        });

    }

    private function mapInputPlatformToInteger($platform = 'live')
    {
        $platform = strtolower($platform);
        switch ($platform) {
            case 'online':
                return MovieLocation::PLATFORM_ONLINE;
                break;
            case 'tobeAnnounced':
                return MovieLocation::PLATFORM_TO_BE_ANNOUNCED;
                break;
            default:
                return MovieLocation::PLATFORM_LIVE;
                break;
        }
    }

    public function getMoreMovies()
    {
        $movies = Movie::with(['images'])
            ->where('is_published', 1)
            ->orderBy('id', 'DESC')->offset(15)->limit(333)->paginate();
        return ($movies);
//        return $this->showAll($movies, 200);
    }

    public function getSimilarMovies($movie)
    {
        $movie = Movie::with(['artistes'=>function($ev){$ev->select('id','name');}])->where('id', $movie)
//            ->select(['id','status','age_restriction'])
            ->first();
        $data = [];
        $i = -1;
        $data['id'] = $movie->id;
        $data['status'] = $movie->movie_status_id;
        $data['name'] = $movie->name;
        $data['description'] = $movie->description;
        $data['age_restriction'] = $movie->age_restriction;
        foreach ($movie->artistes as $artiste){
            $i++;
            $data['artiste'][$i] = [
                'id' => $artiste->id,
                'name' => $artiste->name];
        }
        $similar = Movie::with(['images'])
            ->where(function ($sim) use ($data) {
                $sim->where('id','!=', $data['id']);
                $sim->orWhere('age_restriction', $data['age_restriction']);
                $sim->orWhere('name', 'LIKE', '%' . $data['name'] . '%');
                $sim->orWhere('description', 'LIKE', '%' . $data['description'] . '%');
            })
            ->where('is_published', '=', 1)
            ->where('id', '!=', $data['id'])
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->get();
        return ($similar);
    }

    public function getMovieImage(Movie $movie)
    {
        $images = $movie->images;
        return $this->showAll($images);
    }

    public function saveMovieImage(Request $request, Movie $movie)
    {

        if ($movie) {
            $data = $request->all();
            if (is_null($data) || count($data) === 0) {
                return $this->errorResponse('Cannot submit empty form. Please try again');
            }

            if ($request->has('banner')) {
                $image_url = $this->storeImage($request->file('banner'), Image::IMAGE_TYPES[0]);
                if (!$image_url) {
                    return $this->errorResponse('Image upload failed.');
                }
                $movie->images()->create([
                    'image_url' => $image_url,
                    'tag' => 'banner'
                ]);
                return $this->showMessage('Image uploaded successfully.');
            }
            if ($request->has('thumb')) {
                $image_url = $this->storeImage($request->file('thumb'), Image::IMAGE_TYPES[1]);
                if (!$image_url) {
                    return $this->errorResponse('Image upload failed.');
                }
                $movie->images()->create([
                    'image_url' => $image_url,
                    'tag' => 'thumb'
                ]);
                return $this->showMessage('Image uploaded successfully.');
            }
            if ($request->has('mobile')) {
                $image_url = $this->storeImage($request->file('mobile'), Image::IMAGE_TYPES[2]);
                if (!$image_url) {
                    return $this->errorResponse('Image upload failed.');
                }
                $movie->images()->create([
                    'image_url' => $image_url,
                    'tag' => 'mobile'
                ]);
                return $this->showMessage('Image uploaded successfully.');
            }
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
