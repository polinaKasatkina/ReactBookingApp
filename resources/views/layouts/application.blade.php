<!DOCTYPE html>
<html lang="en">
@include('layouts.application._head')

<body>

<div class="container">

    <div class="row">
      @include('layouts.application._header')
      @section('menu')
        @include('layouts.application._menu')
      @show

      {{--<div class="header-banner">--}}
      {{--<p @if(Route::is('index') || Route::is('search')) class="top" @endif>@yield('title')</p>--}}
      {{--</div>--}}
    </div>

    @yield('content')

</div>

@include('layouts.application._footer')

@yield('script')
</body>
</html>
