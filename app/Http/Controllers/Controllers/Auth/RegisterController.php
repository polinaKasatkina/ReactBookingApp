<?php

namespace App\Http\Controllers\Auth;

//use App\Mail\Welcome;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

//    /**
//     * Where to redirect users after registration.
//     *
//     * @var string
//     */
//    protected $redirectTo = '/booking/place';


    protected function redirectTo()
    {

        if (isset($_COOKIE['booking_request'])) {
            return '/booking/place';
        }

        return '/profile';

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

//    protected function redirectTo()
//    {
//        return '/booking/place';
//    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Register new account.
     *
     * @param Request $request
     * @return User
     */
    protected function register(Request $request)
    {

        try {

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'discount' => 1
            ]);

            Log::info('[' . date('Y-m-d H:i:s') . '] RegisterController:register:: New user was registered at ' . time());

        } catch (\Exception $exception) {

            logger()->error($exception);
            Log::error('[' . date('Y-m-d H:i:s') . '] RegisterController:register:: Unable to create new user.');

            return;
        }

        auth()->login($user);

//      Mail::to($user)->send(new Welcome($user));

        return response()->json(Auth::user());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'discount' => 1
        ]);
    }
}
