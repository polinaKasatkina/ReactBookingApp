<form method="post" enctype="multipart/form-data" action="{{ url('profile', $profile->id) }}">
  {{ method_field('PATCH') }}
  {{ csrf_field() }}

  <div class="row">


    <div class="booking-details-block">

      <div class="row">
        <div class="col-lg-6">
          <div class="row">
            <div class="col-lg-5">
              <label for="email">Email address:</label>
            </div>
            <div class="col-lg-7">
              <input type="email" class="form-control" name="email" id="email" value="{{ $profile->email ?  : '' }}">
            </div>
          </div>
          @if ($errors->has('email'))
            <p class="text-danger"><i>{{ $errors->first('email') }}</i></p>
          @endif
        </div>
        <div class="col-lg-6">
          <div class="row">
            <div class="col-lg-5">
              <label for="title">Title:</label>
            </div>
            <div class="col-lg-7">
              <div class="select-wrapper">
                <select name="title" id="title" class="form-control">
                  <option value="Mr" {{ $profile->title == 'Mr' ? 'selected' : '' }}>Mr</option>
                  <option value="Mrs" {{ $profile->title == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                  <option value="Miss" {{ $profile->title == 'Miss' ? 'selected' : '' }}>Miss</option>
                  <option value="Ms" {{ $profile->title == 'Ms' ? 'selected' : '' }}>Ms</option>
                  <option value="Dr" {{ $profile->title == 'Dr' ? 'selected' : '' }}>Dr</option>
                  <option value="other" {{ $profile->title == 'other' ? 'selected' : '' }}>Other</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="row">
            <div class="col-lg-5">
              <label for="first_name">First name:</label>
            </div>
            <div class="col-lg-7">
              <input type="text" class="form-control" name="first_name" id="first_name"
                     value="{{ $profile->first_name ? : '' }}">
            </div>
          </div>
          @if ($errors->has('first_name'))
            <p class="text-danger"><i>{{ $errors->first('first_name') }}</i></p>
          @endif
        </div>
        <div class="col-lg-6">
          <div class="row">
            <div class="col-lg-5">
              <label for="last_name">Last name:</label>
            </div>
            <div class="col-lg-7">
              <input type="text" class="form-control" name="last_name" id="last_name"
                     value="{{ $profile->last_name ? : '' }}">
            </div>
          </div>
          @if ($errors->has('last_name'))
            <p class="text-danger"><i>{{ $errors->first('last_name') }}</i></p>
          @endif
        </div>
      </div>

    </div>

    <div class="booking-details-block">

      <div class="row">
        <div class="col-lg-6">
          <div class="row">
            <div class="col-lg-5">
              <label for="company">Company</label>
            </div>
            <div class="col-lg-7">
              <input type="text" class="form-control" name="company" id="company"
                     value="{{ $profile->company ? : '' }}">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="row">
            <div class="col-lg-5">
              <label for="address">Address</label>
            </div>
            <div class="col-lg-7">
              <input type="text" class="form-control" name="address" id="address"
                     value="{{ $profile->address ? : '' }}">
            </div>
          </div>
          @if ($errors->has('address'))
            <p class="text-danger"><i>{{ $errors->first('address') }}</i></p>
          @endif
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="row">
            <div class="col-lg-5">
              <label for="city">City/Town</label>
            </div>
            <div class="col-lg-7">
              <input type="text" class="form-control" name="city" id="city" value="{{ $profile->city ? : '' }}">
            </div>
          </div>
          @if ($errors->has('city'))
            <p class="text-danger"><i>{{ $errors->first('city') }}</i></p>
          @endif
        </div>
        <div class="col-lg-6">
          <div class="row">
            <div class="col-lg-5">
              <label for="postcode">Postcode</label>
            </div>
            <div class="col-lg-7">
              <input type="text" class="form-control" name="postcode" id="postcode"
                     value="{{ $profile->postcode ? : '' }}">
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4">
          <label for="region">Region / State</label>
          <input type="text" class="form-control" name="region" id="region" value="{{ $profile->region ? : '' }}">
        </div>
        <div class="col-lg-4">
          <label for="country">Country</label>
          <input type="text" class="form-control" name="country" id="country" value="{{ $profile->country ? : '' }}">
          @if ($errors->has('country'))
            <p class="text-danger"><i>{{ $errors->first('country') }}</i></p>
          @endif
        </div>
        <div class="col-lg-4">
          <label for="phone">Phone number</label>
          <input type="text" class="form-control" name="phone" id="phone" value="{{ $profile->phone ? : '' }}">
          @if ($errors->has('phone'))
            <p class="text-danger"><i>{{ $errors->first('phone') }}</i></p>
          @endif
        </div>
      </div>


    </div>


    <div class="form-group">
      <input type="submit" name="commit" value="Save" class="btn btn-uppercourt">
    </div>

  </div>
</form>
