<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.application._head')


<body>

<div class="container">
  <div class="row">

    @include('layouts.application._header')
    @section('menu')
      @include('layouts.application._menu')
    @show



  </div>
</div>

@include('layouts.application._footer')


<script src="{{asset('js/booking_place.js')}}"></script>
</body>
</html>
