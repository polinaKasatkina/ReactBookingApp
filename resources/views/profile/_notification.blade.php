<div class="col-sm-3 hidden-xs">
    <h4>
        Уведомления
    </h4>
    <p>
        Согласие на получение уведомлений о ресторанах и пользователях, на которых вы подписались
    </p>
</div>
<div class="col-sm-9 col-xs-12">
    <form method="post" action="{{ url("profile/" . $profile->id . "/edit/notice") }}">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}
        <div class="row">
            <div class="col-lg-9">
                <div class="form-group">
                    <label class="label-light" for="notifications">
                        <input type="checkbox" value="" name="notifications" id="notifications">
                        Согласен(на) на получение уведомлений
                    </label>
                </div>
                <div class="form-group">
                    <input type="submit" name="commit" value="Обновить" class="btn btn-orange">
                </div>
            </div>
        </div>
    </form>
</div>