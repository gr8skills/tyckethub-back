<?php

namespace App\Http\Controllers\Artistes;

use App\Http\Controllers\ApiController;
use App\Models\Artiste;

class ArtisteEventController extends ApiController
{
    public function index(Artiste $artiste)
    {
        $events = $artiste->events()
            ->with(['location', 'location.state', 'location.country', 'tags'])
            ->get();
        return $this->showAll($events);
    }
}
