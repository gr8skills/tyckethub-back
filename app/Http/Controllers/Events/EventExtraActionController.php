<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\ApiController;
use App\Models\Event;
use App\Models\EventLocation;
use App\Traits\ImageHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class EventExtraActionController extends ApiController
{
    use ImageHelper;

    public function eventTitleExists($title)
    {
        $title = strtolower($title);
        $exist = false;
        $events_titles = Event::all('name')->toArray();
//        dd($events_titles);
        $events_titles_array = array_column($events_titles, 'name');
        foreach ($events_titles_array as $_title) {
            if ($title === strtolower($_title)) {
                $exist = true;
                break;
            }
        }
        return $exist;
    }

    public function storeOnlinePlatform(Request $request, Event $event)
    {
        if ($event->location->platform !== EventLocation::PLATFORM_ONLINE) {
            return $this->errorResponse('Sorry. This event will not be hosted online');
        }

        $request->validate([
            'platform_name' => ['required'],
            'title' => ['required'],
            'platform_url' => ['required'],
            'description' => ['required']
        ]);

        $location = $event->location;
        $platform = $location->onlinePlatforms()->create([
            'platform_name' => $request->get('platform_name'),
            'title' => $request->get('title'),
            'platform_url' => $request->get('platform_url'),
            'description' => $request->get('description'),
        ]);

        if (!$platform) {
            return $this->errorResponse('Error adding event platform. Please try again later.');
        }
        return $this->showOne($platform);
    }

    public function storeOnlinePlatformExtra(Request $request, Event $event)
    {
        $location = $event->location;

        if ($location->platform !== EventLocation::PLATFORM_ONLINE) {
            return $this->errorResponse('Sorry. This event will not be hosted online');
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

    public function publishEvent(Request $request, Event $event)
    {
        $errors = new \stdClass();

        if ($event->images()->count() === 0) {
            $errors->image = 'Event has no uploaded images. Please upload the necessary image for the event';
        } elseif ($event->images()->count() < 3) {
            $errors->image = 'Some important images for your event are yet to be uploaded. ';
        }
        if ($event->location->platform === EventLocation::PLATFORM_ONLINE) {
            $location = $event->location;
            if ($location->onlinePlatforms()->count() === 0) {
                $errors->platform = 'Event need to have online platform. Please add at least one';
            }
        }
        if ($event->tickets()->count() === 0) {
            $errors->ticket = 'Event needs to have tickets. Please add event tickets before publishing';
        } else {
            $event->tickets()->each(function ($ticket) use (&$errors) {
                if (is_null($ticket->setting)) {
                    $errors->ticket_setting = 'One or more of your event tickets have no ticket settings set up';
                }
            });
        }

        $encoded_errors = json_encode($errors);
        $array_errors = json_decode($encoded_errors, true);

        if (count($array_errors) > 0) {
            return $this->errorResponse($encoded_errors);
        }

        $event_organizer = $event->organizer()->first();
        if (!$event->uid) {
            $event->event_link = getenv('APP_URL') . '/event/' . $event->uid;
        }
        if (!$event->organizer_link) {
            $event->organizer_link = getenv('APP_URL') . '/o/' . $event_organizer->uid;
        }

        $event->is_completed = Event::IS_COMPLETED_TRUE;
        $event->visibility = $request->get('visibility');
        $event->schedule = $request->get('schedule');
        if ((int)$request->get('schedule') !== 1) {
            if ($request->has('schedule_date') && $request->has('schedule_time')) {
                $event->schedule_time = Carbon::parse($request->get('schedule_date'). ' ' . $request->get('schedule_time'));
            } else {
                return $this->errorResponse('Please provide scheduled date and time to publish the event');
            }
        }
        $event->save();

        return $this->showOne($event);
    }

    public function unPublishEvent(Request $request, Event $event)
    {
        try {
            $event->is_completed = Event::IS_COMPLETED_OFF;
            $event->save();

            return $this->showMessage('Operation successfully');

        } catch (\Throwable $exception) {
            return $this->errorResponse('Operation failed please try again');
        }
    }
}
