<?php

namespace App\Traits;

use Asm89\Stack\CorsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponser {

    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code = 422)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        if ($collection->isEmpty()) {
            return $this->successResponse(['data' => $collection], $code);
        }

        $collection = $this->sortData($collection);

        if ($collection->first()->transformer) {
            $transformer = $collection->first()->transformer;
            $collection = $this->transformData($collection, $transformer);
            return $this->successResponse($collection, $code);
        }
        return $this->successResponse(['data' => $collection], $code);

    }

    protected function showOne(Model $model, $code = 200)
    {
        if (!$model->transformer){
            return $this->successResponse(['data' => $model], $code);
        }
        $transformer = $model->transformer;
        $model = $this->transformData($model, $transformer);
        return $this->successResponse($model, $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], $code);
    }

    protected function sortData(Collection $collection) {
        if (request()->has('sort_by')) {
            $attribute = request()->sort_by;
            $collection = $collection->sortBy($attribute);
        }
        return $collection;
    }
    protected function transformData($data, $transformer) {
        $transformation = fractal($data, new $transformer);
        return $transformation->toArray();
    }

    protected function showUserToken($credentials, $code = 200) {
        return $this->successResponse(['data' => $credentials], $code);
    }

//    private function addRequestHeader()
//    {
//        app(CorsService::class)->addActualRequestHeaders($response, $request);
//    }
}
