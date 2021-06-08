<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Models\EventLocation;
use App\Models\EventTag;
use App\Models\Movie;
use App\Traits\ImageHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
//                return $this->errorResponse($e->getMessage());
            }
        }
    }

    public function show($event)
    {
        $event = Event::with([
            'artistes',
            'categories',
            'images',
            'location',
            'location.country',
            'location.state',
            'location.onlinePlatforms',
            'status',
            'tags',
            'tickets',
            'tickets.setting'
        ])->findOrFail($event);
        return $this->showOne($event);
    }

    public function update(Request $request, Event $event)
    {
        $error = true;
        $data = $request->all();

        $event->fill($data);
        if ($event->isDirty()) {
            DB::transaction(function () use (&$event, &$data, &$error) {
                $event->save();
                $this->saveEventData($event, $data, 'update');
                $error = false;
                return $error;
            });

            if (!$error) {
                return $this->showOne($event);
            }
            return $this->errorResponse('Update failed. Please try again later');
        }
        return $this->errorResponse('No change was made to the event details');
    }

    public function destroy(Event $event)
    {
        try {
            $event->delete();

            return $this->showOne($event);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    private function saveEventData($event, $payload, $action = 'store')
    {
        DB::transaction(function () use (&$event, &$payload, &$action) {
            if (!is_null($payload['category_ids']) && isset($payload['category_ids']) > 0) {
                foreach ($payload['category_ids'] as $category_id) {
                    if ($action === 'update') {
                        $event->categories->each(function ($cat) use (&$event) {
                            $event->categories()->detach($cat->id);
                        });
                    }
                    $event->categories()->attach($category_id);
                }
            }

//            dump('Artistes id payload ', $payload['artiste_ids']);
            if (!is_null($payload['artiste_ids']) && isset($payload['artiste_ids']) > 0) {
                foreach ($payload['artiste_ids'] as $artiste_id) {
                    if ($action === 'update') {
                        $event->artistes->each(function ($art) use (&$event) {
                            $event->artistes()->detach($art->id);
                        });
                    }
                    $event->artistes()->attach($artiste_id);
                }
            }

            if (!is_null($payload['tags']) && count($payload['tags']) > 0) {
                $available_tag_names = EventTag::all(['id', 'name'])->pluck('name', 'id')->toArray();

                foreach ($payload['tags'] as $tag) {
                    $tag = strtolower($tag);

                    if (in_array($tag, $available_tag_names)) {
                        $tag_id = array_search($tag, $available_tag_names);

                        if ($action === 'update') {
                            $event->tags()->detach($tag_id);
                        }

                        $event->tags()->attach($tag_id);
                    } else {
                        $tag_new = EventTag::create([
                            'name' => $tag,
                        ]);
                        $event->tags()->attach($tag_new->id);
                    }
                }
            }

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
//                        $event->images()->create([
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
                return EventLocation::PLATFORM_ONLINE;
                break;
            case 'tobeAnnounced':
                return EventLocation::PLATFORM_TO_BE_ANNOUNCED;
                break;
            default:
                return EventLocation::PLATFORM_LIVE;
                break;
        }
    }

    public function getMoreEvents()
    {
        $events = Event::with(['images'])
            ->where('is_published', 1)
            ->orderBy('id', 'DESC')->offset(15)->limit(333)->paginate();
        return ($events);
//        return $this->showAll($events, 200);
    }

    public function getSimilarEvents($event)
    {
        $event = Event::with(['artistes'=>function($ev){$ev->select('id','name');}])->where('id', $event)
//            ->select(['id','status','age_restriction'])
            ->first();
        $data = [];
        $i = -1;
        $data['id'] = $event->id;
        $data['status'] = $event->event_status_id;
        $data['name'] = $event->name;
        $data['description'] = $event->description;
        $data['age_restriction'] = $event->age_restriction;
        foreach ($event->artistes as $artiste){
            $i++;
            $data['artiste'][$i] = [
                'id' => $artiste->id,
                'name' => $artiste->name];
        }
        $similar = Event::with(['images'])
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
}
