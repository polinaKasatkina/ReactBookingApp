<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{

    /**
     * ProfileController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return redirect()->route('profile.edit', ['id' => Auth::user()->id]);
    }

    /**
     * Show current user profile
     *
     * @param User $profile
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $profile)
    {

        return redirect()->route('profile.edit', ['id' => Auth::user()->id]);
//        return view('profile.profile', compact('profile'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $profile
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $profile)
    {

        if ($profile->id != Auth::id())
            return redirect()->to('profile/' . $profile->id);

        return view('profile.edit', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $request
     * @param $profile
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(User $profile, Request $request)
    {

        $profile->update($request->all());

        return response()->json($profile, 200);
    }

    /**
     * @param User $profile
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPasswordForm(User $profile)
    {
        return view('profile.edit', compact('profile'));
    }

    /**
     * @param User $profile
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showNoticeForm(User $profile)
    {
        return view('profile.edit', compact('profile'));
    }

    /**
     * @param User $profile
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAccountForm(User $profile)
    {
        return view('profile.edit', compact('profile'));
    }

    /**
     * @param Request $request
     * @param User $profile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request, User $profile)
    {

        if (!Hash::check($request->current_pass, $profile->getAuthPassword()))
            return response()->json(['status' => 'error', 'message' => 'Current password doesn\'t match!'], 200); //back()->with('error', 'Current password doesn\'t match!');


        if ($request->new_pass != $request->confirm_pass)
            return response()->json(['status' => 'error', 'message' => 'Passwords doesn\'t match!'], 200); //back()->with('error_pass', 'Passwords doesn\'t match!');

        $profile->update([
            'password' => bcrypt($request->new_pass)
        ]);

        return response()->json(['status' => 'success', 'message' => 'Password successfully updated'], 200); //back()->with('notice', 'Password successfully updated');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateNotifications()
    {
        return back()->with('notice', 'Successfully updated');
    }

    /**
     * @param User $profile
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function softDelete(User $profile)
    {
        if ($profile->delete()) {

            // TODO delete customer details in Stripe
//            $customer = $stripe->customers()->delete('cus_4EBumIjyaKooft');

            return response()->json(['status' => 'success', 'message' => ''], 200); //redirect()->to('login');
        }

        Log::error('[' . date('Y-m-d H:i:s') . '] ProfileController:softDelete:: Error occurred. User was not deleted.');
        return response()->json(['status' => 'error', 'message' => 'Error occurred. User was not deleted.'], 200); //back()->with('notice', 'Error occurred. User was not deleted.');
    }
}
