@extends('layouts.application')

@section('content')

  <div class="col-lg-10 col-lg-offset-1 bookings-list" style="margin-top: 20px;">
    <div id="bookings"></div>
  </div>

@endsection
@section('title')
  Your Bookings
@endsection

@section('script')
  <script src="{{asset('js/bookings.js')}}"></script>
@endsection
