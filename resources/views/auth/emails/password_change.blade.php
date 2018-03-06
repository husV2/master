<p>{{ trans('auth.password_recently_changed') }}</p><br>
<strong style="padding:20px">{{$ip}} -  {{$time}}</strong>
    <br>
<h2>{{ trans('auth.password_recently_changed_if_you') }}</h2>
<br>
<strong><a style="padding:20px; color:#58a051" href="{{ $link = url('password/change/confirm', $token) }}"> {{ trans('auth.password_press_here_to_confirm') }} </a></strong>
