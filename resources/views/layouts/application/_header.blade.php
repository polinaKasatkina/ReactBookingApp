<div class="header-wrapper clearfix">

  <!-- BEGIN .top-bar -->
  <div class="top-bar clearfix">

    <ul class="social-icons pull-left"><li><a href="https://twitter.com/uppercourt"><span id="twitter_icon"></span></a></li><li><a href="https://www.facebook.com/uppercourtkemerton/"><span id="facebook_icon"></span></a></li><li><a href="https://plus.google.com/105299621264345986963/about?hl=en"><span id="googleplus_icon"></span></a></li></ul>
    <!-- BEGIN .gmap-btn-wrapper -->
    <div class="gmap-btn-wrapper">
      <a href="#" class="gmap-btn"></a>
      <div class="gmap-curve"></div>
      <!-- END .gmap-btn-wrapper -->
    </div>

    <!-- BEGIN .top-menu-wrapper -->
    <div class="top-menu-wrapper pull-right clearfix map-on">
      @if (Auth::user())
        <div class="btn-group">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-user" aria-hidden="true"></i>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
          </button>
          <ul class="dropdown-menu">
            <li><a href="{{ url("profile/" . Auth::user()->id ."/edit") }}">Account</a></li>
            <li><a href="{{ url("profile/" . Auth::user()->id ."/bookings") }}">Bookings</a></li>
            <li>
              <a href="{{ url("logout") }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="dropdown-item">
                Logout
              </a>
              <form id="logout-form" action="{{ url("logout") }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
            </li>
          </ul>
        </div>
      @else
        <div id="login-btn-bar">
          <a href="{{ url("login") }}">Sign in</a> or <a href="{{ url("register") }}">Sign up</a>
        </div>

        @endif
            <!-- END .top-menu-wrapper -->
    </div>

    <!-- END .top-bar -->
  </div>

  <div id="title-wrapper">
    <h1>
      <a href="https://www.uppercourt.co.uk"><img src="https://www.uppercourt.co.uk/wp-content/uploads/2018/04/3@2x.jpg" alt="" width="220" height="144"></a>
    </h1>
  </div>




  <!-- END .header-wrapper -->
</div>
