<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::all();

        return view('admin.users.list', compact('users'));
    }

    public function show(User $user)
    {

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'content' => 'required|max:230'
        ]);

        Booking::create($request->all());

        return redirect()->to($this->redirectTo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Booking  $booking
     * @return Response
     */
    public function edit(Booking $booking)
    {
        return view('admin.bookings.edit', compact('bookings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Booking $bookings)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        $bookings->update($request->all());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Booking $bookings)
    {
        $bookings->delete();

        return redirect()->to($this->redirectTo);
    }
}
