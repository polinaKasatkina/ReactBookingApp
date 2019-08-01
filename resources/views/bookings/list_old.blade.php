<table class="table table-responsive table-bookings">
  <thead>
  <tr>
    <th>Order ID</th>
    <th>Properties</th>
    <th>Dates</th>
    <th>Payment</th>
    <th>Action</th>
  </tr>
  </thead>
  <tbody>
  @if ($bookings)
    @foreach($bookings as $booking)
      <?php
      $createdAt = new DateTime($booking->created_at);
      $nowDate = new DateTime();
      $dDiff = $createdAt->diff($nowDate);
      ?>
      <tr>
        <td>{{ $booking->id }}</td>
        <td>
          @foreach(json_decode($booking->property_ids) as $property_id)
            <?php $propertyObj = \App\Models\Property::where('property_id', $property_id)->first(); ?>
            @if (!empty($propertyObj)) {{ $propertyObj->name }} <br/>@endif
          @endforeach
        </td>
        <td>{{ date('d.m.Y', strtotime($booking->start_date)) }} - <br/>{{ date('d.m.Y', strtotime($booking->end_date)) }}</td>
        <?php
        $totalPrice = 0;
        foreach(json_decode($booking->property_ids) as $property_id) {
          $price = \App\Models\PropertyPrice::where('property_id', '=', $property_id)
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
        ?>

        <?php
        $className = '';
        $buttonText = '';
        switch ($booking->status) {
          case 0:
            $className = 'info';
            $buttonText = 'Waiting for the payment';
            break;
          case 1:
            $className = 'warning';
            $buttonText = 'Deposit paid';
            break;
          case 2:
            $className = 'success';
            $buttonText = 'Full price paid';
            break;
          case 3:
            $className = 'danger';
            $buttonText = 'Cancelled';
            break;
          default:
            $className = 'info';
            $buttonText = 'Waiting for the payment';
            break;
        }
        ?>

        <td>
          Total price: &pound;{{ $totalPrice }}<br/>
          @if ($buttonText == 'Deposit paid' || $buttonText == 'Full price paid')
            <?php
            $payment = \App\Models\BookingPayment::where('booking_id', $booking->id)->first();
            ?>
            Paid: @if ($payment) &pound;{{ $payment->amount }} @endif
          @endif
        </td>
        <td>
          <a href="{{ url("/profile/" . $profile->id . "/bookings/" . $booking->id) }}" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open"></i> Edit</a>

          <a href="{{ url("/profile/" . $profile->id . "/bookings/" . $booking->id) }}" class="btn btn-<?=$className?> btn-xs"><i class="glyphicon glyphicon-gbp"></i> <?=$buttonText?></a>
          <?php $bookingPayment = new \App\Models\BookingPayment(); ?>
          @if (empty($bookingPayment->getPaymentDate($booking->id)) && ($dDiff->days < 2))

          @endif
        </td>
      </tr>
    @endforeach
  @else
    <tr>
      <td colspan="4">No bookings added</td>
    </tr>
  @endif
  </tbody>
</table>
