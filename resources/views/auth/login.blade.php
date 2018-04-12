@extends('layouts.auth')
@section('content')
@if(isset($failed) && ($failed))
<script>
    $(function(){
        $('#gridSystemModal').modal('show');
    });
</script>
@endif
<div class="row">
    <div class="col-sm-8" id="login-form">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <div class="col-sm-11">
                    <input id="username" type="username" class="form-control" name="username" value="{{ old('username') }}" placeholder="{{ trans('auth.username') }}">
                    @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-sm-1 helpiconcol">
                    <span class="info_sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="{{trans('main.login_username')}}">&#x24d8;</span>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-11">
                    <input id="password" type="password" class="form-control" name="password" placeholder="{{ trans('auth.password') }}">
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-sm-1 helpiconcol">
                    <span class="info_sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="{{trans('main.login_password')}}">&#x24d8;</span>
                </div>
            </div>
            <div class="form-group d_btnsign">
                <div class="col-sm-5">
                    <button type="submit" id="btn_signin" class="btn btn-default">{{ trans('auth.login') }}</button>
                </div>
                <div class="col-sm-3">
                    <a href="{{ url('/password/reset') }}">{{ trans('auth.forgotten') }}</a>
                </div>
            </div>
        </form>
        <div class="d_newuser">
            <span class="text-faded text-button" data-toggle="modal" data-target="#gridSystemModal">{{ trans('auth.newuser') }}</span>
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        </div>
    </div>


    <div class="col-sm-4" id="apurigif">
        <img src="{{ URL::asset('img/apuri_welcome.gif') }}" class="img-responsive helper_welcome">
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <p class="text-faded copyright_placement">&#9400; {{ trans('auth.hus_copyright') }}</p>
    </div>
</div>
</div>
<?= isset($modal) ? $modal : "" ?>
@endsection