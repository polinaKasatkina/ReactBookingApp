<div class="row">

  <div class="col-lg-12">
    <form method="post" action="{{ url("profile/" . $profile->id . "/edit/account") }}">
      {{ method_field('PATCH') }}
      {{ csrf_field() }}


      <div class="booking-details-block">

        <div class="row">
          <div class="col-lg-5">
            <label>Delete account</label>
          </div>

          <div class="col-lg-7">
            <input type="submit" name="commit" value="Delete" class="btn btn-lg btn-uppercourt">
          </div>
        </div>

      </div>


    </form>
  </div>
</div>
