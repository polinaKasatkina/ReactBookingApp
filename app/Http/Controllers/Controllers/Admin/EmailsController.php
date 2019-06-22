<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\Emails;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\Welcome;
use App\Mail\ConfirmedBooking;
use App\Mail\ProvisionalBooking;

class EmailsController extends Controller
{

    public function index()
    {

        $emails = Emails::all();

        return view('admin.emails.list', compact('emails'));
    }

    public function show(Emails $email)
    {
        return view('admin.emails.show', compact('email'));
    }

    public function send(Emails $email)
    {

        $user = User::where('email', $email->to)->first();
        $booking = Booking::where('created_at', $email->date)->first();

        switch ($email->subject) {
            case "Welcome" :
                Mail::to($user)->send(new Welcome($user));
                break;
            case "Provisional Booking" :
                Mail::to($user)->send(new ProvisionalBooking($user, $booking));
                break;
            case "Confirmed Booking":
                Mail::to($user)->send(new ConfirmedBooking($user, $booking));
                break;
        }

        return back()->with('notice', 'Email sent!');
    }

}
