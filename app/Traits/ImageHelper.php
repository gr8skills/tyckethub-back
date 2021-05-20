<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\Image as Imageable;

trait ImageHelper
{
    public function storeImage($image_file, $type = 'any', $disk = 'images')
    {
        $type = strtolower($type);
        switch ($type) {
            case Imageable::IMAGE_TYPES[0]:
                $image_name = time() . '_banner_' . $image_file->getClientOriginalName() . $image_file->getExtension();
                break;
            case Imageable::IMAGE_TYPES[1]:
                $image_name = time() . '_thumb_' . $image_file->getClientOriginalName() . $image_file->getExtension();
                break;
            case Imageable::IMAGE_TYPES[2]:
                $image_name = time() . '_mobile_' . $image_file->getClientOriginalName() . $image_file->getExtension();
                break;
            default:
                $image_name = time() . $image_file->getClientOriginalName() . $image_file->getExtension();
                break;
        }


        try {
            $image = Image::make($image_file);
            $image->resize(100, 110, function ($const){
                $const->aspectRatio();
            })->save($image_name);
            $filePath = public_path('/images');
            $image->move($filePath, $image);
            return $image_name;
        } catch (\Exception $e) {
            return null;
        }

    }

    public function removeStoredImage($file_name, $disk='images')
    {
        if (!Storage::disk($disk)->exists($file_name)) {
            return false;
        }
        Storage::disk($disk)->delete($file_name);

        return true;
    }
}

