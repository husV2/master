<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('auth.header') }}</title>
	
	<script src="{{ URL::asset('js/jquery.min.js') }}"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="{{ URL::asset('js/auth.js') }}"></script>
	<script src="{{ URL::asset('js/registrationOrganizationController.js') }}"></script>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="{{ URL::asset('css/auth.css') }}">
        <script>

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
	
</head>
	<body>
            <div class="container">
			<div class="row">
				<div class="col-xs-8 col-sm-6">
					<img src="{{ URL::asset('img/logo.png') }}" alt="Image not found" class="img-responsive">
				</div>
				<div class="col-xs-4 col-sm-6 text-right">
					<a href="#">FI</a>
					<span class="stickblock">|</span>
					<a href="#">EN</a>
				</div>
			</div>
			<div class="row" id="circletainer_row">
				<div class="col-sm-12">
					<div class="circletainer">
						<p>{{trans('main.welcome')}}</p>
					</div>
				</div>
			</div>
                    @yield('content')
            </div><!-- End of container -->
		
	</body>
</html>
