<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>NONSENSE</title>
	
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="{{ URL::asset('js/home.js') }}"></script>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="{{ URL::asset('css/home.css') }}">
	
</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<img src="{{ URL::asset('img/logo.png') }}" class="img-responsive">
				</div>
				<div class="col-sm-6 text-right">
					<a href="#">FI</a>
					<span class="stickblock">|</span>
					<a href="#">EN</a>
					<span class="stickblock">|</span>
					<a href="#">SE</a>
				</div>
			</div>
			
			<div class="row row_profile">
				<div class="col-sm-3 col-sm-offset-7">
					<div class="col-sm-12 text-right"><a class="link_usernav" href="#">Profiili</a></div>
					<div class="col-sm-12 text-right"><a class="link_usernav" href="#">Tulostaulukko</a></div>
					<div class="col-sm-12 text-right"><a class="link_usernav" href="#">Oma treeni</a></div>
					<div class="col-sm-12 text-right"><a class="link_usernav" href="#">Unohtunut linkki</a></div>
				</div>
				<div class="col-sm-2">
					<div class="col-sm-12">
						<div class="avatarbox_border">
							<div class="avatarbox_image"><img src="{{ URL::asset('img/placeholder_user.png') }}" class="img-responsive"></div>
							<div class="avatarbox_ball"></div>
						</div>
					</div>	
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-3 col-exercise">
					<div class="col-sm-12 col-title text-left"><p class="text_title">Minitreeni ohjelmasi <span class="glyphicon glyphicon-question-sign"></span></p></div>
					<div class="col-sm-12"><div class="exblock text-center"><a href="#" class="text_exercise">treeni x <span class="glyphicon glyphicon-chevron-down"></span></a></div></div>
					<div class="col-sm-12"><div class="exblock text-center"><a href="#" class="text_exercise">treeni x <span class="glyphicon glyphicon-chevron-down"></span></a></div></div>
					<div class="col-sm-12"><div class="exblock text-center"><a href="#" class="text_exercise">treeni x <span class="glyphicon glyphicon-chevron-down"></span></a></div></div>
					<div class="col-sm-12"><div class="exblock text-center"><a href="#" class="text_exercise">treeni x <span class="glyphicon glyphicon-chevron-down"></span></a></div></div>
					<div class="col-sm-12"><div class="exblock text-center"><a href="#" class="text_exercise">treeni x <span class="glyphicon glyphicon-chevron-down"></span></a></div></div>
					<div class="col-sm-12"><div class="exblock text-center"><a href="#" class="text_exercise">treeni x <span class="glyphicon glyphicon-chevron-down"></span></a></div></div>
					<div class="col-sm-12 text-right"><div class="link_placement"><a class="link_modify">Muokkaa <span class="glyphicon glyphicon-chevron-right"></span></a></div></div>
				</div>
				<div class="col-sm-4">
					<div class="col-sm-12 text-right col-title"><p class="text_title">Jokukuukausi <span class="glyphicon glyphicon-question-sign"></span></p></div>
					<div class="col-sm-12">
						<div class="calblock"><img src="{{ URL::asset('img/placeholder_calendar.png') }}" class="img-responsive"></div>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="col-sm-12">
						<div class="timeball"><div class="timeblock"><p class="text_timer">Seuraavaan treeniin</p><p class="text_timeleft">00:00</p></div></div>
					</div>
					<div class="col-sm-5">puhekupla</div>
					<div class="col-sm-7"><img src="{{ URL::asset('img/placeholder_avatar.png') }}" class="img-responsive"></div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<p class="text-faded">&#9400; HUS minitreeni 2016</p>
				</div>			
			</div>
		</div>
	</body>
</html>