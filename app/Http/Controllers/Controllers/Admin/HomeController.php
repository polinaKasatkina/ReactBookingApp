<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index()
    {

        $bookings = Booking::orderBy('created_at', 'desc')->take(5)->get();
        $users = User::orderBy('created_at', 'desc')->take(5)->get();

//        if (Auth::user()->role_id == 1) {
            return view('admin.index', compact('bookings', 'users'));
//        }

//        return redirect('/');

    }
}
