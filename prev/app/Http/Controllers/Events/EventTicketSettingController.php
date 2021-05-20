<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\EventTicket;
use App\Models\EventTicketSetting;
use Illuminate\Http\Request;

class EventTicketSettingController extends Controller
{
    public function index(EventTicket $ticket, EventTicketSetting $see)
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(EventTicketSetting $setting)
    {
        //
    }

    public function update(Request $request, EventTicketSetting $setting)
    {
        //
    }

    public function destroy(EventTicketSetting $setting)
    {
        //
    }
}
