<div class="aside col-sm-3 col-xs-12 pull-right">
        <div class="sideBlock profile col-sm-12">
            <div class="row">
                <div class="profile__info col-sm-12">
                    <p><i class="fa fa-user" aria-hidden="true"></i>{{ $profile->first_name }} {{ $profile->last_name }}</p>
                </div>
                <div class="profile__head col-sm-12">
                    <div class="row">
                        <nav class="nav">
                            <li><a href="{{ url("profile/" . $profile->id ."/edit") }}">Edit</a></li>
                            <li><a href="{{ url("profile/" . $profile->id ."/bookings") }}">Bookings</a></li>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
</div>
