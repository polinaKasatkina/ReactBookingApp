@extends('layouts.application')

@section('content')

  <div class="col-lg-10 col-lg-offset-1">

    <ul class="results-nav" style="margin-top: 20px; padding-bottom: 30px;">
      <li>Results</li>
      <li>Booking Details</li>
      <li class="active">Payment</li>
    </ul>

    <div class="booking-details">

      @if(session('notice'))
        <div class="alert alert-dismissible alert-success col-xs-12 payment-success">
          <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fa fa-info-circle pull-left" aria-hidden="true"></i>
            <p>{{ session('notice') }}</p>
        </div>
      @endif

      @if(session('warning'))
        <div class="alert alert-dismissible alert-danger col-xs-12">
          <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fa fa-info-circle pull-left" aria-hidden="true"></i>
            <p>{{ session('warning') }}</p>
        </div>
      @endif

      <h2 class="underline">Booking #{{ $booking->id }}</h2>

      <div class="row">
        <div class="col-lg-12">
          <ul class="place-booking-properties">
            @foreach(json_decode($booking->property_ids) as $property)
              <li data-property="{{ $property }}">
                <?php $propertyObj = \App\Models\Property::where('property_id', $property)->first(); ?>
                @if (!empty($propertyObj)){{ $propertyObj->name }}@endif
                <input type="hidden" name="propertyId[]" value="{{ $property }}"/>
              </li>
            @endforeach
          </ul>
          <ul class="pull-right place-booking-properties" style="margin-top: -10px;">
            <li><strong>Total price:</strong> &pound;{{ $totalPrice }}
            </li>
          </ul>
        </div>
      </div>

      <div class="row" style="margin-top: 20px">
        <div class="col-lg-6">
          <p><strong>Booking details</strong></p>
          <table class="table table-bookings">
            <tbody>
            <tr>
              <td>Adults:</td>
              <td>{{ $booking->adults }}</td>
            </tr>
            <tr>
              <td>Children:</td>
              <td>{{ $booking->children }}</td>
            </tr>
            <tr>
              <td>Infants:</td>
              <td>{{ $booking->infants }}</td>
            </tr>
            @if ($booking->pet)
              <tr>
                <td>Pet:</td>
                <td><input type="checkbox" disabled checked/></td>
              </tr>
            @endif
            <tr>
              <td>Check in:</td>
              <td>{{ date('d.m.Y', strtotime($booking->start_date)) }}</td>
            </tr>
            <tr>
              <td>Check out:</td>
              <td>{{ date('d.m.Y', strtotime($booking->end_date)) }}</td>
            </tr>
            </tbody>
          </table>
        </div>

        <div class="col-lg-6">
          <p><strong>Customer details</strong></p>
          <table class="table table-bookings">
            <tbody>
            <tr>
              <td>Name:</td>
              <td>{{ $profile->first_name }} {{ $profile->last_name }}</td>
            </tr>
            <tr>
              <td>Email:</td>
              <td>{{ $profile->email }}</td>
            </tr>
            <tr>
              <td>Phone:</td>
              <td>{{ $profile->phone }}</td>
            </tr>

            @if ($profile->company)
              <tr>
                <td>Company:</td>
                <td>{{ $profile->company }}</td>
              </tr>
            @endif

            <tr>
              <td>Address:</td>
              <td>
                {{ $profile->address }}<br/>
                {{ $profile->city }}<br/>
                {{ $profile->region }}<br/>
                {{ $profile->country }}<br/>
                {{ $profile->postcode }}
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>

      <?php $bookingPayment = new \App\Models\BookingPayment(); ?>
      <?php
      $createdAt = new DateTime($booking->created_at);
      $bookingDate = new DateTime($booking->start_date);
      $nowDate = new DateTime();
      //        $dDiff = $bookingDate->diff($nowDate);

      $amount = $bookingDate->diff($nowDate)->days < 56 ? $totalPrice : $totalPrice * 0.3;

      ?>
      @if (!$bookingPayment->getPaymentDate($booking->id) && $booking->status == 0)
        <div class="row" style="margin-top: 20px">

          <form method="post" action="{{ url('booking/pay') }}" class="col-lg-12">
            {{ csrf_field() }}

            <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
            <input type="hidden" name="amount" value="{{ $amount }}"/>

            <h2 class="underline">Payment method</h2>

            @if ($bookingDate->diff($nowDate)->days >= 14)
              <p>You have {{ $booking->payment_days }} days to make the payment. Please finalise your payment
                before <?= $createdAt->add(new DateInterval('P' . $booking->payment_days . 'D'))->format('d.m.Y') ?>
                .</p>
            @endif

            <div class="booking-details-block">

              <div class="row">
                <div class="col-lg-5">
                  <label>Card holder name</label>
                  <input name="card_holder_name" class="form-control" type="text"
                         value="{{ $card ? $card['name'] : '' }}"/>
                  @if ($errors->has('card_holder_name'))
                    <p class="text-danger"><i>{{ $errors->first('card_holder_name') }}</i></p>
                  @endif
                </div>
                <div class="col-lg-4">
                  <label>Card number</label>
                  <input type="text" name="card_number" class="form-control"
                         value="{{ $card ? "**** **** **** " . $card['last4'] : '' }}"/>
                  @if ($errors->has('card_number'))
                    <p class="text-danger"><i>{{ $errors->first('card_number') }}</i></p>
                  @endif
                </div>
                <div class="col-lg-1">
                  <label>CVV</label>
                  <input type="text" name="card_cvv" class="form-control"/>
                  @if ($errors->has('card_cvv'))
                    <p class="text-danger"><i>{{ $errors->first('card_cvv') }}</i></p>
                  @endif
                </div>
                <div class="col-lg-2">
                  <label>Month/Year</label>
                  <?php
                    if ($card) {
                      $exp_month = $card['exp_month'] > 9 ? $card['exp_month'] : 0 . $card['exp_month'];
                      $exp_year = $card ? substr($card['exp_year'], -2) : '';
                    } else {
                      $exp_month = '';
                      $exp_year = '';
                    }

                  ?>
                  <input type="text" name="card_month_year" class="form-control" placeholder="MM/YY"
                         value="{{ $card ? $exp_month . '/' . $exp_year : '' }}"/>
                  @if ($errors->has('card_month_year'))
                    <p class="text-danger"><i>{{ $errors->first('card_month_year') }}</i></p>
                  @endif
                </div>
              </div>


              <div class="row">
                <div class="col-lg-12">
                  <ul class="payment-methods">
                    <li class="visa"></li>
                    <li class="american-express"></li>
                    <li class="paypal"></li>
                    <li class="discover"></li>
                    <li class="maestro"></li>
                  </ul>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <label>
                    <input type="checkbox" name="terms"/> Accept <a href="https://www.uppercourt.co.uk/terms-conditions/"
                                                                    target="_blank">Terms & Conditions</a>
                  </label>
                  @if ($errors->has('terms'))
                    <p class="text-danger"><i>{{ $errors->first('terms') }}</i></p>
                  @endif
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <button class="btn btn-uppercourt pull-right submit_payment" id="pay-deposit">
                    @if ($bookingDate->diff($nowDate)->days < 56)
                      Pay full price
                    @else
                      Pay deposit
                    @endif
                  </button>
                </div>
              </div>


            </div>


          </form>

        </div>
      @endif

      @if(session('notice') || $booking->status !== 0)
        <div class="row text-center">
          <a href="{{ url('/') }}" class="btn btn-light">Return home</a>
          <a href="{{ url('profile/' . $profile->id . '/bookings/' . $booking->id . '/invoice') }}" class="btn btn-light">View invoice</a>
        </div>
      @endif


    </div>


  </div>


  </div>
@endsection

@section('title')
  Booking #{{ $booking->id }}
@endsection
