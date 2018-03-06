@extends('layouts.auth')

@section('content')
<script src="{{ URL::asset('js/registrationOrganizationController.js') }}"></script>
                <div class="panel-body">
                    <form class="form-horizontal" id="regForm" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('uname') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">{{ trans('auth.username') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}">

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('fname') ? ' has-error' : '' }}">
                            <label for="firstname" class="col-md-4 control-label">{{ trans('auth.fname') }}</label>

                            <div class="col-md-6">
                                <input id="firstname" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}">

                                @if ($errors->has('firstname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label for="lastname" class="col-md-4 control-label">{{ trans('auth.lname') }}</label>

                            <div class="col-md-6">
                                <input id="lastname" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}">

                                @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">{{ trans('auth.email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('organization') ? ' has-error' : '' }}">
                            <label for="organization" class="col-md-4 control-label">{{ trans('auth.organization') }}</label>

                            <div class="col-md-6">
                                <select name="group1" class="organization_select">
                                    <option value="none" selected="selected" class="initial">{{ trans('auth.organization_choose') }}</option>
                                    <?php foreach($data as $key => $or): ?>
                                        <option value="1_<?= $key ?>"><?=$or?></option>
                                    <?php endforeach; ?>
                                <select>
                                <select name="group2" class="organization_select hidden">
                                    <option value="none" selected="selected" class="initial">{{ trans('auth.organization_choose') }}</option>
                                <select>
                                <select name="group3" class="organization_select hidden">
                                    <option value="none" selected="selected" class="initial">{{ trans('auth.organization_choose') }}</option>
                                <select>
                                <select name="group4" class="organization_select hidden">
                                    <option value="none" selected="selected" class="initial">{{ trans('auth.organization_choose') }}</option>
                                <select>
                                <select name="group5" class="organization_select hidden">
                                    <option value="none" selected="selected" class="initial">{{ trans('auth.organization_choose') }}</option>
                                <select>

                                @if ($errors->has('organization'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('organization') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">{{ trans('auth.password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">{{ trans('auth.confpass') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" id="regButton" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>{{ trans('auth.register') }}
                                </button>
                            </div>
                        </div>
                        <a href="{{ url('/login') }}">{{ trans('main.back') }}</a>
                    </form>
                </div>
@endsection
