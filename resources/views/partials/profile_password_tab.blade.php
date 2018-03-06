<div class="container-fluid">
    <div class="row">
        <div class="col-md-12"><h3>{{trans('main.change_password')}}</h3></div>
        <div class='col-md-12 edit-section'>
            <form method="post" id="update-form" action="{{url('edit/profile')}}" role="form">
                {{ csrf_field() }}
                <div class="form-group">
                        <span class="help-block old-password">
                        <strong></strong>
                        </span>
                    <label for="old_password"><h4>{{trans('auth.old_password') }}</h4></label>
                    <input type="password" name="old_password" class="form-control" />
                </div>
                <div class="form-group">
                    <span class="help-block new-password">
                        <strong></strong>
                    </span>
                    <label for="new_password"><h4>{{trans('auth.new_password') }}</h4></label>
                    <input type="password" name="new_password" class="form-control" />
                </div>
                <div class="form-group">
                    <span class="help-block confirm-password">
                        <strong></strong>
                    </span>
                    <label for="confirm_password"><h4>{{trans('auth.confirm_password') }}</h4></label>
                    <input type="password" name="confirm_password" class="form-control" />
                </div>
                <button type="button" class="btn btn-success pw-change-button">{{trans('main.save')}}</button>
            </form>
            <span class="success-block" style="display: none"><strong></strong></span>
        </div>
    </div>
</div>