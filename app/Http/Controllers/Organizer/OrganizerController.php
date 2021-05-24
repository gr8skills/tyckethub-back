<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\ApiController;
use App\Models\Organizer;
use Illuminate\Http\Request;

class OrganizerController extends ApiController
{
    public function index()
    {
        $organizers = Organizer::all();
        return $this->showAll($organizers);
    }

    public function show(Organizer $organizer)
    {
        return $this->showOne($organizer);
    }

}
