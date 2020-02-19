<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>
    @hasSection('title')
    Upper Court Bookings: @yield('title')
    @else
      Upper Court Bookings
    @endif
  </title>
  <meta name="description" content="@yield('description')">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @stack('meta-tags')


  @stack('styles')

      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>


<body>

<div class="container">

  <div class="row">

    <table style="width: 100%">
      <tr>
        <td>
          <p style="color: #999;">Seller</p>
          <div>
            <p><strong>Upper Court / Bookings</strong></p>
            <p>Company Registered No: </p>
            <p>Registered Office:<br/>
              Upper Court,<br/>
              Kemerton, <br/>
              Tewkesbury, <br/>
              Gloucestershire <br/>
              GL20 7HY
            </p>
          </div>
        </td>
        <td style="text-align: right;">
          <img src="https://www.uppercourt.co.uk/wp-content/uploads/2018/04/3@2x.jpg" style="width: 200px;"/>
        </td>
      </tr>
    </table>
    <table style="width: 100%; display: none;">
      <tr>
        <td>
          <p style="color: #999;">Buyer</p>
          <div>
            <p><strong>{{ $profile->first_name }} {{ $profile->last_name }}</strong></p>
            <p>{{ $profile->address }}<br/>
              {{ $profile->city }}<br/>
              {{ $profile->region }}
            </p>
          </div>
        </td>
      </tr>
    </table>
    <table style="width: 100%;">
      <tr>
        <td colspan="3"><p style="color: #999;">Buyer</p></td>
      </tr>
      <tr>
        <td colspan="3"><strong>{{ $profile->first_name }} {{ $profile->last_name }}</strong></td>
      </tr>
      <tr>
        <td colspan="3">{{ $profile->address }}</td>
      </tr>
      <tr>
        <td>{{ $profile->city }}</td>
        <td><strong>Invoice</strong></td>
        <td style="text-align: right;">{{ $booking->id }}</td>
      </tr>
      <tr>
        <td>{{ $profile->region }}</td>
        <td>Invoice Date</td>
        <td style="text-align: right;">{{ $booking->created_at->format('d.m.Y') }}</td>
      </tr>
      </tr>
      <tr>
        <td>{{ $profile->country }}</td>
        <td>Discount</td>
        <td style="text-align: right;">&pound;{{ \App\Helpers\PriceHelper::formatPrice($discountValue) }}</td>
      <tr>
        <td>{{ $profile->postcode }}</td>
        <td>Order Amount (GBR)</td>
        <td style="text-align: right;">&pound;{{ \App\Helpers\PriceHelper::formatPrice($totalPrice) }}</td>
      </tr>
    </table>

    <table style="width: 100%; margin-top: 30px;" cellspacing="0">
      <thead>
      <tr>
        <th style="padding: 8px;">Product</th>
        <th style="padding: 8px;">Net.</th>
        <th style="padding: 8px;">Qty.</th>
        <th style="padding: 8px;">VAT %</th>
        <th style="padding: 8px;">VAT</th>
        <th style="padding: 8px;">Total</th>
      </tr>
      </thead>
      <tbody>
      @if ($booking->property_ids)
        <?php $subtotal = 0; $totalVAT = 0; ?>
        @foreach(json_decode($booking->property_ids) as $property)
          <tr>
            <?php $propertyObj = \App\Models\Property::where('property_id', $property)->first(); ?>
            @if (!empty($propertyObj))
              <td style="padding: 8px; border-top: 1px solid #ccc;">{{ $propertyObj->name }} (excluding VAT)</td>
            @endif
            <?php

            $fullPrice = \App\Helpers\PriceHelper::getPropertyPrice($property, $booking);

            $subtotal += $fullPrice * 0.8;
            $totalVAT += $fullPrice * 0.2;

            ?>
            <td style="padding: 8px; border-top: 1px solid #ccc;">
              &pound;{{ $fullPrice*0.8 }}
            </td>
            <td style="padding: 8px; border-top: 1px solid #ccc;">1</td>
            <td style="padding: 8px; border-top: 1px solid #ccc;">20%</td>
            <td style="padding: 8px; border-top: 1px solid #ccc;">&pound;{{ \App\Helpers\PriceHelper::formatPrice($fullPrice*0.2) }}</td>
            <td style="padding: 8px; border-top: 1px solid #ccc; text-align: right;">&pound;{{ \App\Helpers\PriceHelper::formatPrice($fullPrice) }}</td>
          </tr>
        @endforeach
      @endif
      </tbody>
    </table>

    <table class="table table-total" style="float: right; width: 40%;">
      <tr>
        <td style="padding: 8px;">Subtotal</td>
        <td style="text-align: right; padding: 8px;">&pound;{{ \App\Helpers\PriceHelper::formatPrice($subtotal) }}</td>
      </tr>
      <tr>
        <td style="padding: 8px;">VAT (20%)</td>
        <td style="text-align: right; padding: 8px;">&pound;{{ \App\Helpers\PriceHelper::formatPrice($totalVAT) }}</td>
      </tr>
      <tr>
        <td style="padding: 8px;"><strong>Total</strong></td>
        <td style="text-align: right; padding: 8px;"><strong>&pound;{{ \App\Helpers\PriceHelper::formatPrice($totalPrice) }}</strong></td>
      </tr>
    </table>

    <div style="clear: both;"></div>

    <div class="row" style="margin-top: 30px;">
      <div class="col-lg-12">
        <p><strong>Payment method</strong></p>
        <p>Credit Card (Stripe)</p>
      </div>
    </div>

  </div>


  <script
      src="https://code.jquery.com/jquery-2.2.4.min.js"
      integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
      crossorigin="anonymous"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script type="application/javascript"
          src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
  <script type="application/javascript" src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
  <script type="application/javascript" src="{{ asset('js/jquery.maskedinput.min.js') }}"></script>
  <script src="{{ asset('js/app/script.js') }}"></script>


@stack('scripts')
</body>
</html>
