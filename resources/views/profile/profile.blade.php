@extends('layouts.application')

@section('content')

    @include('profile._sidebar')

    <div class="col-sm-9 col-xs-12">

        <div class="">
            <div class="col-xs-12 std-block userInfo">

                <h1>User info</h1>

                <p>First name: {{ $profile->first_name }}</p>
                <p>Last name: {{ $profile->last_name }}</p>
                <p>Title: {{ $profile->title ? : '-' }}</p>
                <p>Email: {{ $profile->email ? : '-' }}</p>
                <p>Company: {{ $profile->company ? : '-' }}</p>
                <p>Address: {{ $profile->address ? : '-' }}</p>
                <p>City/Town: {{ $profile->city ? : '-' }}</p>
                <p>Postcode: {{ $profile->postcode ? : '-' }}</p>
                <p>Region/State: {{ $profile->region ? : '-' }}</p>
                <p>Country: {{ $profile->country ? : '-' }}</p>
                <p>Phone: {{ $profile->email ? : '-' }}</p>
            </div>
        </div>
    </div>
@endsection
