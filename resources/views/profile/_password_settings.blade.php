<form method="post" action="{{ url("profile/" . $profile->id . "/edit/password") }}">
    {{ method_field('PATCH') }}
    {{ csrf_field() }}
    <div class="row">

        <div class="col-lg-12">

            <div class="booking-details-block">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-5">
                                <label class="label-light" for="current_pass">Current password</label>
                            </div>
                            <div class="col-lg-7">
                                <input class="form-control" required="required" type="password" value="" name="current_pass" id="current_pass">
                            </div>
                        </div>
                        @if ($errors->has('current_pass'))
                            <span class="help-block text-danger">
                        <strong>{{ $errors->first('current_pass') }}</strong>
                    </span>
                        @endif

                        @if(session('error'))
                            <span class="text-danger help-block">
                        {{ session('error') }}
                    </span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-5">
                                <label class="label-light" for="new_pass">New password</label>
                            </div>
                            <div class="col-lg-7">
                                <input class="form-control" required="required" type="password" value="" name="new_pass" id="new_pass">
                            </div>
                        </div>
                        @if ($errors->has('new_pass'))
                            <span class="help-block text-danger">
                        <strong>{{ $errors->first('new_pass') }}</strong>
                    </span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-5">
                                <label class="label-light" for="confirm_pass">Repeat password</label>
                            </div>
                            <div class="col-lg-7">
                                <input class="form-control" required="required" type="password" value="" name="confirm_pass" id="confirm_pass">
                            </div>
                        </div>
                        @if ($errors->has('confirm_pass'))
                            <span class="help-block text-danger">
                        <strong>{{ $errors->first('confirm_pass') }}</strong>
                    </span>
                        @endif

                        @if(session('error_pass'))
                            <span class="text-danger help-block">
                        {{ session('error_pass') }}
                    </span>
                        @endif
                    </div>
                </div>
            </div>


            <div class="form-group">
                <input type="submit" name="commit" value="Update" class="btn btn-uppercourt">
            </div>
        </div>
    </div>
</form>
