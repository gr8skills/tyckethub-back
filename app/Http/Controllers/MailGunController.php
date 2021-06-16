<?php

namespace App\Http\Controllers;

use App\Jobs\TicketReceipt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Mailgun\Mailgun;

class MailGunController extends Controller
{
    public function index()
    {
        $user = User::find(1)->toArray();
        $this->dispatch(new TicketReceipt($user));
        dd('Mail Sent Successfully');
    }

    public static function getHostUrl()
    {
        $protocol = 'https://';
        $uri = 'tyckethub.com';
        return $protocol . $uri . '/';
    }
}
