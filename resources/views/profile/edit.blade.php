@extends('layouts.application')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="welcome-block">
                <h3>Hi, {{ $profile->first_name }} {{ $profile->last_name }}</h3>
            </div>
        </div>
    </div>

    <div id="profile"></div>

@endsection
@section('title')
    Profile
@endsection

@section('script')
    <script src="{{asset('js/profile.js')}}"></script>
@endsection
