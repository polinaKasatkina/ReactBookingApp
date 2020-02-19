<?php

namespace App\Http\Controllers;

use App\Models\Property;
use DateTime;
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
use DateInterval;
use App\Helpers\PriceHelper;

class BookingController extends Controller
{

    public function addBookingCookie()
    {

        setcookie('booking_request', json_encode(request(['productIDs', 'holiday_type', 'checkin', 'checkout', 'adults', 'children', 'infants'])), time() + 60 * 60 * 24 * 2);

    }

    public function placeBooking()
    {

        $booking_request = isset($_COOKIE['booking_request']) ? json_decode($_COOKIE['booking_request']) : [];
        $totalPrice = $booking_request ? PriceHelper::getTotalPrice($booking_request) : 0;

        return view('bookings.place', compact('booking_request', 'totalPrice'));

    }

    public function save(Request $request, User $user)
    {


        $data = json_decode($request->getContent(), true);

        if (Auth::check()) { // update user info

            $user = Auth::user();
            $user->update([
                'first_name' => $data['first_name'],
                'email' => $data['email'],
                'title' => $data['title'],
                'last_name' => $data['last_name'],
                'address' => $data['address'],
                'city' => $data['city'],
                'region' => $data['region'],
                'country' => $data['country'],
                'postcode' => $data['postcode'],
                'phone' => $data['phone']
            ]);

            $user_id = Auth::user()->id;

        } else { // create new user

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make(uniqid()),
                'title' => $data['title'],
                'address' => $dat['address'],
                'city' => $data['city'],
                'region' => $data['region'],
                'country' => $data['country'],
                'postcode' => $data['postcode'],
                'phone' => $data['phone'],
                'discount' => 0,
                'company' => $data['company']
            ]);

            $user_id = $user->id;

            Auth::loginUsingId($user_id, true);

        }

//        $booking_request = json_decode($_COOKIE['booking_request']);

        $booking = Booking::create([
            'property_ids' => json_encode($data['property_ids']),
            'start_date' => date('Y-m-d', strtotime(str_replace('/', '.', $data['start_date']))),
            'end_date' => date('Y-m-d', strtotime(str_replace('/', '.', $data['end_date']))),
            'adults' => $data['adults'],
            'children' => $data['children'],
            'arrival_time' => '16:00',
            'departure_time' => '12:00',
            'options' => '{}',
            'notes' => $data['notes'],
            'user_id' => $user_id,
            'infants'  => $data['infants'],
            'pet'     => 0,
            'holiday_type' => $data['holiday_type'],
            'status' => 0,
            'payment_days' => 2
        ]);

//        unset($_COOKIE['booking_request']);
//        setcookie('booking_request', null, -1, '/');


//        Mail::to($user)->send(new ProvisionalBooking($user, $booking));
//        Mail::to('herfords@uppercourt.co.uk')->send(new AdminProvisionalBooking($user, $booking));

        return response()->json(['userData' => Auth::user(), 'bookingData' => $booking]); //redirect()->to('/profile/' . $user_id . '/bookings/' . $booking->id);

    }

    public function bookingsList(User $profile)
    {

        $result = [];

        $bookings = Booking::where('user_id', $profile->id)
            ->orderBy('id', 'desc')
            ->get();


        foreach ($bookings as $booking) {


            $properties = [];

            foreach(json_decode($booking->property_ids) as $property_id) {

                $propertyObj = Property::where('property_id', $property_id)->first();

                $properties[] = $propertyObj;
            }

            $totalPrice = PriceHelper::getTotalPrice($booking->property_ids);

            $result[] = [
                'id' => $booking->id,
                'start_date' => date('d.m.Y', strtotime($booking->start_date)),
                'end_date'   => date('d.m.Y', strtotime($booking->end_date)),
                'properties' => $properties,
                'total_price' => $totalPrice
            ];
        }

        return response()->json($result);

    }

    public function info(User $profile, Booking $booking)
    {

        $totalPrice = PriceHelper::getTotalPrice($booking, false);

        $card = [];
        if ($profile->stripe_id) {
            $stripe = Stripe::make(env('STRIPE_SECRET'));

            $customer = $stripe->customers()->find($profile->stripe_id);
            $card = $customer ? $customer["sources"]['data'][0] : [];
        }

        $properties = [];

        foreach(json_decode($booking->property_ids) as $property) {

            $properties[] = $propertyObj = Property::where('property_id', $property)->first();

        }

        $bookingPayment = new BookingPayment();

        $createdAt = new DateTime($booking->created_at);
        $bookingDate = new DateTime($booking->start_date);
        $nowDate = new DateTime();

        $booking->amount = $bookingDate->diff($nowDate)->days < 56 ? $totalPrice : $totalPrice * 0.3;
        $booking->amount = number_format($booking->amount, "2");

        $booking->paymentDate = $bookingPayment->getPaymentDate($booking->id);
        $booking->payTillDate = $createdAt->add(new DateInterval('P' . $booking->payment_days . 'D'))->format('d.m.Y');

        return response()->json(compact('booking', 'totalPrice', 'card', 'properties'));

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


//                Mail::to($user)->send(new ConfirmedBooking($user, $booking));

                return response()->json(['status' => 'success', 'message' => 'Payment was successful!']);

//                return back()->with('notice', 'Payment was successful!');

            } else {

                Log::info('[' . date('Y-m-d H:i:s') . '] BookingController:payDeposit:: There was some errors in taking payment with Stripe');

                return response()->json(['status' => 'erroe', 'message' => 'There was some errors in taking payment. Please try again later!']);

//                return back()->with('warning', 'There was some errors in taking payment. Please try again later!');
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

        $totalPrice = PriceHelper::getTotalPrice($booking, false);


        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        $pdf = PDF::loadView('bookings.invoice', compact('profile', 'booking', 'totalPrice'))->save( storage_path('invoices/') . 'invoice_' . $booking->id . '.pdf' );

        return response()->file(storage_path('invoices/') . 'invoice_' . $booking->id . '.pdf',
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }


}
