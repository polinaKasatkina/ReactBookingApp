<?php

namespace App\Http\Controllers;

use DateTime;
use App\Mail\ConfirmedBooking;
use App\Mail\ProvisionalBooking;
use App\Mail\AdminProvisionalBooking;
use App\Models\BookingPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;
use Cartalyst\Stripe\Stripe;
use Illuminate\Support\Facades\Log;
use App\Models\PropertyPrice;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade as PDF;

class BookingController extends Controller
{

    public function addBookingCookie()
    {

        setcookie('booking_request', json_encode(request(['productIDs', 'holiday_type', 'checkin', 'checkout', 'adults', 'children', 'infants'])), time() + 60 * 60 * 24 * 2);

    }

    public function placeBooking()
    {

        $booking_request = isset($_COOKIE['booking_request']) ? json_decode($_COOKIE['booking_request']) : [];
        $totalPrice = 0;


        foreach ($booking_request->productIDs as $property) {

            $checkInDate = str_replace('/', '.', $booking_request->checkIn);

            $price = PropertyPrice::where('property_id', '=', $property)
                ->where('start_date', '<', date('Y-m-d', strtotime($checkInDate)))
                ->where('end_date', '>', date('Y-m-d', strtotime($checkInDate)))
                ->first();


            if ($price) {
                switch ($booking_request->holiday_type) {
                    case '3':
                        $totalPrice += (float)str_replace(',', '', $price->mid_week_price);
                        break;
                    case '4':
                        $totalPrice += (float)str_replace(',', '', $price->mid_week_price);
                        break;
                    case '7':
                        $totalPrice += (float)str_replace(',', '', $price->week_price);
                        break;
                    case '14':
                        $totalPrice += (((float)str_replace(',', '', $price->week_price)) * 2);
                        break;
                }

            }


        }

        return view('bookings.place', compact('booking_request', 'totalPrice'));

    }

    public function save(Request $request, User $user)
    {

        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email',
            'title' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'phone' => 'required'
        ]);


        if (!Auth::check()) {
            $request->validate([
                'email' => 'unique:users',
            ]);
        }

        if (Auth::check()) { // update user info

            $user = Auth::user();
            $user->update([
                'first_name' => $request->first_name,
                'email' => $request->email,
                'title' => $request->title,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'city' => $request->city,
                'region' => $request->region,
                'country' => $request->country,
                'postcode' => $request->postcode,
                'phone' => $request->phone
            ]);

            $user_id = Auth::user()->id;

        } else { // create new user

            $user = User::create([
                'first_name' => request('first_name'),
                'last_name' => request('last_name'),
                'email' => request('email'),
                'password' => Hash::make(uniqid()),
                'title' => request('title'),
                'address' => request('address'),
                'city' => request('city'),
                'region' => request('region'),
                'country' => request('country'),
                'postcode' => request('postcode'),
                'phone' => request('phone'),
                'discount' => 0,
                'company' => request('company')
            ]);

            $user_id = $user->id;

        }

        $booking_request = json_decode($_COOKIE['booking_request']);

        $booking = Booking::create([
            'property_ids' => json_encode($booking_request->productIDs),
            'start_date' => date('Y-m-d', strtotime(str_replace('/', '.', $booking_request->checkIn))),
            'end_date' => date('Y-m-d', strtotime(str_replace('/', '.', $booking_request->checkOut))),
            'adults' => $booking_request->adults,
            'children' => $booking_request->children,
            'arrival_time' => '16:00',
            'departure_time' => '12:00',
            'options' => '{}',
            'notes' => request('notes'),
            'user_id' => $user_id,
            'infants'  => $booking_request->infants,
            'pet'     => $request->pet ? 1 : 0,
            'holiday_type' => $booking_request->holiday_type,
            'status' => 0,
            'payment_days' => 2
        ]);

        unset($_COOKIE['booking_request']);
        setcookie('booking_request', null, -1, '/');

        Auth::loginUsingId($user_id, true);


        Mail::to($user)->send(new ProvisionalBooking($user, $booking));
        Mail::to('herfords@uppercourt.co.uk')->send(new AdminProvisionalBooking($user, $booking));

        return redirect()->to('/profile/' . $user_id . '/bookings/' . $booking->id);

    }

    public function bookingsList(User $profile)
    {

        $bookings = Booking::where('user_id', $profile->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('bookings.list', compact('bookings', 'profile'));
    }

    public function info(User $profile, Booking $booking)
    {

        $totalPrice = 0;

        foreach (json_decode($booking->property_ids) as $property) {

            $price = PropertyPrice::where('property_id', '=', $property)
                ->where('start_date', '<', $booking->start_date)
                ->where('end_date', '>', $booking->start_date)
                ->first();


            if ($price) {
                switch ($booking->holiday_type) {
                    case '3':
                        $totalPrice += (float)str_replace(',', '', $price->mid_week_price);
                        break;
                    case '4':
                        $totalPrice += (float)str_replace(',', '', $price->mid_week_price);
                        break;
                    case '7':
                        $totalPrice += (float)str_replace(',', '', $price->week_price);
                        break;
                    case '14':
                        $totalPrice += (((float)str_replace(',', '', $price->week_price)) * 2);
                        break;
                    default:
                        $totalPrice += (float)str_replace(',', '', $price->week_price);
                        break;
                }

            }


        }

        $card = [];
        if ($profile->stripe_id) {
            $stripe = Stripe::make(env('STRIPE_SECRET'));

            $customer = $stripe->customers()->find($profile->stripe_id);
            $card = $customer ? $customer["sources"]['data'][0] : [];
        }


        return view('bookings.booking', compact('profile', 'booking', 'totalPrice', 'card'));
    }


    public function payDeposit(Request $request)
    {
        $request->validate([
            'card_holder_name' => 'required',
            'card_number' => 'required',
            'card_cvv' => 'required',
            'card_month_year' => 'required',
            'terms'    => 'required'
        ]);

        $stripe = Stripe::make(env('STRIPE_SECRET'));

        $user = Auth::user();

        $stripe_customer_id = $user->stripe_id;

        if (!$user->stripe_id) {

            // creating customer in stripe
            $customer = $stripe->customers()->create([
                'email' => $user->email,
            ]);

            // updating user with stripe_id value
            $userData = User::find($user->id);
            $userData->stripe_id = $customer['id'];
            $userData->save();

            $exp_date = explode('/', $request->card_month_year);

            //creating customer card
            $token = $stripe->tokens()->create([
                'card' => [
                    'name'   => $request->card_holder_name,
                    'number' => $request->card_number,
                    'exp_month' => $exp_date[0], //$request->card_month,
                    'exp_year' => 20 . $exp_date[1], //$request->card_year,
                    'cvc' => $request->card_cvv
                ],
            ]);

            $card = $stripe->cards()->create($customer['id'], $token['id']);

            $stripe_customer_id = $customer['id'];

        }

            $charge = $stripe->charges()->create([
//                'card' => $card['id'], //$token['id'],
                'customer' => $stripe_customer_id,
                'currency' => 'GBP',
                'amount' => $request->amount,
                'description' => 'Booking #' . $request->booking_id . ' payment',
            ]);



            if ($charge['status'] == 'succeeded') {

                $booking = Booking::find($request->booking_id);

                $bookingDate = new DateTime($booking->start_date);
                $nowDate = new DateTime();

                $booking->status = $bookingDate->diff($nowDate)->days < 56 ? 2 : 1;

                $booking->save();

                // put data in DB
                BookingPayment::create([
                    'booking_id' => $request->booking_id,
                    'stripe_id'  => $charge['id'],
                    'amount'     => $request->amount
                ]);


                Mail::to($user)->send(new ConfirmedBooking($user, $booking));

                return back()->with('notice', 'Payment was successful!');

            } else {

                Log::info('[' . date('Y-m-d H:i:s') . '] BookingController:payDeposit:: There was some errors in taking payment with Stripe');

                return back()->with('warning', 'There was some errors in taking payment. Please try again later!');
            }


    }

    public function updatePaymentDays()
    {

        $booking = Booking::find(request('booking_id'));
        $booking->payment_days = request('paymentDays');
        $booking->save();

    }

    public function invoice(User $profile, Booking $booking)
    {

        $totalPrice = 0;

        foreach (json_decode($booking->property_ids) as $property) {

            $price = PropertyPrice::where('property_id', '=', $property)
                ->where('start_date', '<', $booking->start_date)
                ->where('end_date', '>', $booking->start_date)
                ->first();


            if ($price) {
                switch ($booking->holiday_type) {
                    case '3':
                        $totalPrice += (float)str_replace(',', '', $price->mid_week_price);
                        break;
                    case '4':
                        $totalPrice += (float)str_replace(',', '', $price->mid_week_price);
                        break;
                    case '7':
                        $totalPrice += (float)str_replace(',', '', $price->week_price);
                        break;
                    case '14':
                        $totalPrice += (((float)str_replace(',', '', $price->week_price)) * 2);
                        break;
                    default:
                        $totalPrice += (float)str_replace(',', '', $price->week_price);
                        break;
                }

            }


        }

        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        $pdf = PDF::loadView('bookings.invoice', compact('profile', 'booking', 'totalPrice'))->save( storage_path('invoices/') . 'invoice_' . $booking->id . '.pdf' );

        return response()->file(storage_path('invoices/') . 'invoice_' . $booking->id . '.pdf',
            [
                'Content-Type' => 'application/pdf',
//                'Content-Disposition' => 'attachment; filename="invoice_' . $booking->id . '.pdf"',
            ]
        );
//        return view('bookings.invoice', compact('profile', 'booking', 'totalPrice'));
    }


}
