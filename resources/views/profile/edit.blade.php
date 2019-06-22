@extends('layouts.application')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="welcome-block">
                <h3>Hi, {{ $profile->first_name }} {{ $profile->last_name }}</h3>
            </div>
        </div>
    </div>


    <div class="col-lg-10 col-lg-offset-1" style="margin-top: -42px;">

        <div class="row">
            <div class="col-xs-12 std-block userInfo">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs userInfo__tabs" role="tablist">
                    <li role="presentation"@if(Route::is('profile.edit')) class="active"@endif><a href="{{ url("profile/" . $profile->id . "/edit") }}">Profile info</a></li>
                    <li role="presentation"@if(Route::is('profile.edit.password')) class="active"@endif><a href="{{ url("profile/" . $profile->id . "/edit/password") }}">Password</a></li>
                    <li role="presentation"@if(Route::is('profile.edit.account')) class="active"@endif><a href="{{ url("profile/" . $profile->id . "/edit/account") }}">Account</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content userInfo__content">
                    @if(session('notice'))
                        <div class="alert alert-dismissible alert-success col-xs-12">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <div>
                                <i class="fa fa-info-circle pull-left" aria-hidden="true"></i>
                                <p>{{ session('notice') }}</p>
                            </div>
                        </div>
                    @endif
                    <div role="tabpanel" class="tab-pane @if(Route::is('profile.edit')) active @endif" id="profile">
                        <div class="col-sm-12">
                            @include('profile._profile_settings')
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane @if(Route::is('profile.edit.password')) active @endif" id="pass">
                        <div class="col-sm-12">
                            @include('profile._password_settings')
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane @if(Route::is('profile.edit.notice')) active @endif" id="notifications">
                        <div class="col-sm-12">
                            @include('profile._notification')
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane @if(Route::is('profile.edit.account')) active @endif" id="accoint">
                        <div class="col-sm-12">
                            @include('profile._account')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('title')
    Profile
@endsection
