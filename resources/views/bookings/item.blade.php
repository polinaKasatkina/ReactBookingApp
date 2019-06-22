<ul class="properties">

@foreach ($properties as $items)

  @foreach ($items as $property_id => $item)
    <?php $propertyDetails = \App\Models\Property::where('property_id', $property_id)->first(); ?>
    <li>
      <div class="row">
        <div class="img-container col-lg-3">
          <img src="{{ $propertyDetails->details->img ? : asset('img/cottage-example.png') }}" class="img-responsive" />
        </div>
        <div class="col-lg-6">
          <p class="property-title pull-left">
            {{ $propertyDetails->name }}
          </p>
          <p class="property-price pull-right">
            <?php
            $checkInDate = str_replace('/', '.', request('checkin'));
            $price = \App\Models\PropertyPrice::where('property_id', '=', $property_id)
                ->where('start_date', '<', date('Y-m-d', strtotime($checkInDate)))
                ->where('end_date', '>', date('Y-m-d', strtotime($checkInDate)))
                ->first(); ?>
            @if ($price)
                <?php
                    switch (request('holiday_type')) {
                      case '3':
                        echo '&pound;' . $price->mid_week_price;
                            break;
                      case '4':
                        echo '&pound;' . $price->mid_week_price;
                            break;
                      case '7':
                        echo '&pound;' . $price->week_price;
                            break;
                      case '14':
                        echo '&pound;' . (((float)str_replace(',', '',$price->week_price))*2);
                            break;
                    }
                ?>
            @endif
          </p>
          <div class="property-labels">
            <span class="property-label">{{ $propertyDetails->details->bedrooms }} bedrooms</span>
            <span class="property-label">{{ $propertyDetails->details->bathrooms }} bathrooms</span>
            <span class="property-label">
              Sleeps {{ $propertyDetails->details->capacity_adults + $propertyDetails->details->capacity_children }}
              @if ($propertyDetails->details->capacity_infants)
                and {{ $propertyDetails->details->capacity_infants }} @if ($propertyDetails->details->capacity_infants > 1) infants @else infant @endif
              @endif
            </span>
          </div>
          <div class="property-description">
             {{ $propertyDetails->details->description }}
          </div>
          <?php
            #TODO move it to DB
           $urls = [
             '356234' => 'https://www.uppercourt.co.uk/accommodation/coach-house/',
             '356882' => 'https://www.uppercourt.co.uk/accommodation/courtyard-cottage/',
             '357752' => 'https://www.uppercourt.co.uk/accommodation/stables/',
             '357944' => 'https://www.uppercourt.co.uk/accommodation/the-dovecote/'
           ];
          ?>
          @if (isset($urls[$property_id])) <a href={{ $urls[$property_id] }}" class="property-see-details">See details</a> @endif
        </div>
        <div class="col-lg-3">
          <button class="btn btn-uppercourt add-to-booking" data-property="{{ $property_id }}">Add to booking</button>
        </div>
      </div>

    </li>
  @endforeach
@endforeach
</ul>
