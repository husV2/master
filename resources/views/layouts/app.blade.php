<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')

    <title>HUS Minitreeni DEMO</title>
	
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.min.js"></script>
	<script src="{{ URL::asset('fullcalendar/locale/fi.js') }}"></script>
	<script src="{{ URL::asset('js/Achievement.js') }}"></script>
	<script src="{{ URL::asset('js/home.js') }}"></script>
	<script src="{{ URL::asset('js/linkActivator.js') }}"></script>
	<script>
	$(function () {
		$.ajaxSetup({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
		});
	});
	</script>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="{{ URL::asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/friend_request_message.css') }}">
	<link rel="shortcut icon" href="{{{ asset('img/HUS_favicon.png') }}}">
    <link rel="stylesheet" href="{{ URL::asset('css/friend_request_message.css') }}">
	<link rel="shortcut icon" href="{{ URL::asset('img/HUS_favicon.png') }}">
	<link rel="notification icon" href="{{ URL::asset('img/apuri_notification.png') }}">
    @yield('header')
</head>
	<body>
		@if(!empty(Auth::user()) && Auth::user()->friendRequests(true))
                    @include('partials.message')
		@endif
		
		@if(!isset($nav_countdown))
			@include('partials.training_notification')
		@endif
				
		<div class="container main-container">
			<div class="row">
					<div class="col-md-1 col-md-push-11" style="text-align: right">
						@if(!isset($nav_countdown))
							<p class="cd-clock" id="cd-nav">&nbsp;</p>
						@else
							<p>&nbsp;</p>
						@endif
					</div>
			</div>
			<div class="row">
				<div class="col-sm-3">
					<img src="{{ URL::asset('img/logo.png') }}" class="img-responsive">
				</div>
				<div class="col-sm-9">
					<div class="row" id="nomargin">
						<div class="col-md-7 col-md-offset-1">
							@if(!isset($nav_countdown))
								<div class="cd_cont"><p id="cd-title" style="display: none">Treenin aika!</p>
								<button class="cd-accept" id="cd-ac-nav" style="display: none">Suorita!</button>
								<a class="cd-snooze" id="cd-sn-nav" style="display: none">Myöhemmi</a>
								</div>
							@endif
						</div>
						<div class="col-sm-2">
							<div class="equal navbox">
								<div class="text-right nopads"><a class="link_usernav" href="{{ url('/') }}" id="nav_treeni">{{ trans('main.home') }}</a></div>
								<div class="text-right nopads"><a class="link_usernav" href="{{ url('/charts') }}" id="nav_charts">{{ trans('main.charts') }}</a></div>
                                                                <div class="text-right nopads"><a class="link_usernav" href="{{ url('/profile') }}" id="nav_profile">{{ trans('main.profile') }}</a></div>
								@if(!Auth::guest() && Auth::user()->isAdmin)
									<div class="text-right nopads"><a class="link_usernav" href="{{ url('/admin') }}" id="nav_admin">Ylläpito</a></div>
								@endif
							</div>
						</div>
						<div class="col-sm-2 h_inherit">
								<div class="avatarbox_border" title="{{trans('main.profilepic_change')}}">
                                                                    <div class="avatarbox_image">
                                                                        <a href='#' onClick='show_profilepic_modal()'>
                                                                            <img src="<?= URL::asset('storage/avatars/'.session('profilepic')) ?>" class="img-responsive center-block">
                                                                        </a>
                                                                    </div>
									<div class="avatarbox_ribbon"></div>
								</div>
						</div>
					</div>
				</div>
			</div>
            <div class="row">
				<div class="col-sm-2 col-sm-offset-10 col-logout">
					<div class="link_placement">
                                            @if(!Auth::guest())
						<a class="link_logout" href="{{ url('/logout') }}">Kirjaudu<br>ulos!</a>
                                            @endif
					</div>
				</div>
				@yield('content')
				<div class="row">
					<div class="col-sm-12">
						<div class="copyright_placement"><p class="copyright"><span id="copymark">&copy;</span> HUS Minitreeni 2016</p></div>
					</div>			
				</div>
            </div>
		</div><!-- End of container -->
            <!-- Profile picture change area -->
            @if(!Auth::guest())
                @include('partials.profilepicModal')
                @if($errors->has('profilepic'))
                <script>$('#profilepic-modal').modal('show');</script>
                @endif
                @include('partials.achievement')
            @endif
	</body>
</html>
