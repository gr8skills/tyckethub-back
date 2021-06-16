<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\MovieLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MovieExtraActionController extends ApiController
{
    public function movieTitleExists($title)
    {
        $title = strtolower($title);
        $exist = false;
        $movies_titles = Movie::all('name')->toArray();
//        dd($movies_titles);
        $movies_titles_array = array_column($movies_titles, 'name');
        foreach ($movies_titles_array as $_title) {
            if ($title === strtolower($_title)) {
                $exist = true;
                break;
            }
        }
        return $exist;
    }

    public function storeOnlinePlatform(Request $request, Movie $movie)
    {
        if ($movie->location->platform !== MovieLocation::PLATFORM_ONLINE) {
            return $this->errorResponse('Sorry. This movie will not be hosted online');
        }

        $request->validate([
            'platform_name' => ['required'],
            'title' => ['required'],
            'platform_url' => ['required'],
            'description' => ['required']
        ]);

        $location = $movie->location;
        $platform = $location->onlinePlatforms()->create([
            'platform_name' => $request->get('platform_name'),
            'title' => $request->get('title'),
            'platform_url' => $request->get('platform_url'),
            'description' => $request->get('description'),
        ]);

        if (!$platform) {
            return $this->errorResponse('Error adding movie platform. Please try again later.');
        }
        return $this->showOne($platform);
    }

    public function storeOnlinePlatformExtra(Request $request, Movie $movie)
    {
        $location = $movie->location;

        if ($location->platform !== MovieLocation::PLATFORM_ONLINE) {
            return $this->errorResponse('Sorry. This movie will not be hosted online');
        }

        $platform_extra = $location->onlinePlatformExtra()->create([
            'text' => $request->get('text'),
            'video_url' => $request->get('video_url'),
            'link_title' => $request->get('link_title'),
            'link_url' => $request->get('link_url')
        ]);

        if (!$platform_extra) {
            return $this->errorResponse('Error adding platform extras. Please try again.');
        }

        if ($request->has('image')) {
            $image_path = $this->storeImage($request->file('image'));
            if (!$image_path) {
                return $this->errorResponse('Error saving platorm extra images. Please try again later');
            }
            $platform_extra->image()->create([
                'image_url' => $image_path
            ]);

            $platform_extra->image = $image_path;
            $platform_extra->save();
        }
        return $this->showOne($platform_extra);
    }

    public function publishMovie(Request $request, Movie $movie)
    {
        $errors = new \stdClass();

        if ($movie->images()->count() === 0) {
            $errors->image = 'Movie has no uploaded images. Please upload the necessary image for the movie';
        }
//        } elseif ($movie->images()->count() < 3) {
//            $errors->image = 'Some important images for your movie are yet to be uploaded. ';
//        }
        if ($movie->location->platform === MovieLocation::PLATFORM_ONLINE) {
            $location = $movie->location;
            if ($location->onlinePlatforms()->count() === 0) {
                $errors->platform = 'Movie need to have online platform. Please add at least one';
            }
        }
        if ($movie->tickets()->count() === 0) {
            $errors->ticket = 'Movie needs to have tickets. Please add movie tickets before publishing';
        } else {
            $movie->tickets()->each(function ($ticket) use (&$errors) {
                if (is_null($ticket->setting)) {
                    $errors->ticket_setting = 'One or more of your movie tickets have no ticket settings set up';
                }
            });
        }

        $encoded_errors = json_encode($errors);
        $array_errors = json_decode($encoded_errors, true);

        if (count($array_errors) > 0) {
            return $this->errorResponse($encoded_errors);
        }

        $movie_organizer = $movie->organizer()->first();
        if (!$movie->uid) {
            $movie->movie_link = getenv('APP_URL') . '/movie/' . $movie->id . '/description';
        }
        if (!$movie->organizer_link) {
            $movie->organizer_link = getenv('APP_URL') . '/movie/' . $movie->id . '/description';
        }

        $movie->is_completed = Movie::IS_COMPLETED_TRUE;
        $movie->visibility = $request->get('visibility');
        $movie->schedule = $request->get('schedule');
        if ((int)$request->get('schedule') != 1) {
            if ($request->has('schedule_date') && $request->has('schedule_time')) {
                $movie->schedule_time = Carbon::parse($request->get('schedule_date'). ' ' . $request->get('schedule_time'));
            } else {
                $movie->schedule_time = Carbon::now()->format('Y-m-d'). ' ' . Carbon::now()->format('H-m-s');
//                return $this->errorResponse('Please provide scheduled date and time to publish the movie');
            }
        }
        $movie->save();

        return $this->showOne($movie);
    }

    public function unPublishMovie(Request $request, Movie $movie)
    {
        try {
            $movie->is_completed = Movie::IS_COMPLETED_OFF;
            $movie->save();

            return $this->showMessage('Operation successfully');

        } catch (\Throwable $exception) {
            return $this->errorResponse('Operation failed please try again');
        }
    }
}
