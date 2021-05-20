<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\ApiController;
use App\Models\Event;
use App\Models\Image;
use App\Traits\ImageHelper;
use Illuminate\Http\Request;


class EventImageController extends ApiController
{
    use ImageHelper;

    public function getEventImage(Event $event)
    {
        $images = $event->images;
        return $this->showAll($images);
    }

    public function saveEventImage(Request $request, Event $event)
    {


        if ($event) {
            $data = $request->all();
            if (is_null($data) || count($data) === 0) {
                return $this->errorResponse('Cannot submit empty form. Please try again');
            }
//            return $this->showMessage('I got here successfully.');

            if ($request->has('banner')) {
                $image_url = $this->storeImage($request->file('banner'), Image::IMAGE_TYPES[0]);
                if (!$image_url) {
                    return $this->errorResponse('Image upload failed.');
                }
                $event->images()->create([
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
                $event->images()->create([
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
                $event->images()->create([
                    'image_url' => $image_url,
                    'tag' => 'mobile'
                ]);
                return $this->showMessage('Image uploaded successfully.');
            }
        }
    }
}
