<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Models\HomePageSlide;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Image;
use Symfony\Component\Console\Input\Input;


class HomePageSlideController extends ApiController
{
    public function index()
    {
        $slides = HomePageSlide::all();
        return $this->showAll($slides, 200);
    }

    public function store(Request $request)
    {
        $payload = $request->file('banner');
        $request['name'] = $payload->getClientOriginalName();
        $request['imgFile'] = $payload;
        try {
            $this->validate($request, [
                'name' => 'required',
//                'imgFile' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:4096',
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

        $image = $request['imgFile'];
        $input['name'] = time().'.'.$image->extension();
        $input['active'] = 1;
        $input['description'] = $input['name'] . ' and it is active by default';

        $filePath = public_path('/thumbnails');

        $img = Image::make($image->path());
        $img->resize(1920, 560, function ($const) {
            $const->aspectRatio();
            $const->upsize();
        })->save($filePath.'/'.$input['name']);

        $filePath = public_path('/slides');
        $image->move($filePath, $input['name']);

        $saveDataToDb = HomePageSlide::create([
            'name'=>$input['name'],
            'active'=>$input['active'],
            'description'=>$input['description']
        ]);

        return $this->showMessage('Image uploaded successfully.');
    }

    public function update(Request $request, $slide)
    {
        $slide = HomePageSlide::find($slide);
        try {
            $this->validate($request, [
                'name' => 'required',
                'imgFile' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:4096',
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

        $image = $request->file('imgFile');
        $file = $image;
        if ($file != '') {
            $filePath = public_path('/slides');

            if (file_exists($filePath . $slide->name)) {
                if ($slide->name != '' && $slide->name != null) {
                    $file_old = $filePath . $slide->name;
                    unlink($file_old);
                }
            }
            $input['name'] = time().'.'.$image->extension();
            $filePath = public_path('/thumbnails');

            $img = Image::make($image->path());
            $img->resize(1920, 560, function ($const) {
                $const->aspectRatio();
            })->save($filePath.'/'.$input['name']);

            $filePath = public_path('/slides');
            $image->move($filePath, $input['name']);

            return back()
                ->with('success','Image uploaded')
                ->with('fileName',$input['name']);
        }
    }

    public function show($slide)
    {
        $slide = HomePageSlide::find($slide);
        return $this->showAll($slide, 200);
    }

    public function destroy($slide)
    {
        $slide = HomePageSlide::find($slide);
        try {
            $filePath = public_path('/slides');
            if (file_exists($filePath . $slide->name)) {
                $file_old = $filePath . $slide->name;
                unlink($file_old);
            }
            $slide->delete();
        }catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(), 500);
        }
    }
}
