<?php

namespace App\Http\Controllers\Artistes;

use App\Http\Controllers\ApiController;
use App\Models\Artiste;
use App\Models\Image;
use App\Traits\ImageHelper;
use Illuminate\Http\Request;

class ArtisteImageController extends ApiController
{
    use ImageHelper;

    public function getArtisteImages(Artiste $artiste)
    {
        $images = $artiste->images()->get();
        return $this->showAll($images);
    }

    public function storeArtisteImage(Request $request, Artiste $artiste)
    {
        $data = $request->all();
        if (is_null($data) || count($data) < 1) {
            return $this->errorResponse('Cannot submit empty form. Please try again');
        }

//        Artiste Banner Image
        if ($request->has('banner') && !is_null($request->file('banner'))) {
            $this->checkAndRemovePreviousUploadedFile($artiste, Image::IMAGE_TYPES[0]);

            $image_url = $this->storeImage($request->file('banner'), Image::IMAGE_TYPES[0]);

            if (!$image_url) {
                return $this->errorResponse('Image upload failed.');
            }

            $artiste->images()->create([
                'image_url' => $image_url,
                'tag' => 'banner'
            ]);

            return $this->showMessage('Image uploaded successfully.');
        }

//        Artiste Thumbnail Image
        if ($request->has('thumb') && !is_null($request->file('thumb'))) {
            $this->checkAndRemovePreviousUploadedFile($artiste, Image::IMAGE_TYPES[1]);

            $image_url = $this->storeImage($request->file('thumb'), Image::IMAGE_TYPES[1]);

            if (!$image_url) {
                return $this->errorResponse('Image upload failed.');
            }

            $artiste->images()->create([
                'image_url' => $image_url,
                'tag' => 'thumb'
            ]);

            return $this->showMessage('Image uploaded successfully.');
        }
    }

    private function checkAndRemovePreviousUploadedFile(Artiste $artiste, $tag)
    {
        if ($tag === Image::IMAGE_TYPES[0]) {
            $deleted = false;
            $image_url = $artiste->images()->where('tag', Image::IMAGE_TYPES[0])->first('image_url')->image_url;
            if ($image_url) {
                $deleted = $this->removeStoredImage($image_url);
                if ($deleted) {
                    $artiste->images()->where('tag', Image::IMAGE_TYPES[0])->delete();
                }
            }
        }

        if ($tag === Image::IMAGE_TYPES[1]) {
            $deleted = false;
            $image_url = $artiste->images()->where('tag', Image::IMAGE_TYPES[1])->first('image_url')->image_url;
            if ($image_url) {
                $deleted = $this->removeStoredImage($image_url);
                if ($deleted) {
                    $artiste->images()->where('tag', Image::IMAGE_TYPES[1])->delete();
                }
            }
        }
    }
}
