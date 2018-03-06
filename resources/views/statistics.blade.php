@extends('layouts.app')
@section('header')
<script src="{{URL::asset('js/chart.bundle.min.js')}}"></script>
<script src="{{URL::asset('js/colorLuminance.js')}}"></script>
<link rel="stylesheet" href="{{ URL::asset('css/statistics.css') }}">
@endsection
@section('content')
<div class="container" id="statistics">
    <div class="row">
        <div class="col-md-4 col-sm-12">
			
           <?= isset($friend_activity) ? $friend_activity : "" ?>
        </div>
		<div class="col-md-4 col-sm-12 border_left">
			
			<div class="col-md-12 col-sm-12">
				<h4>{{trans('main.upper_department_rank')}} <span id="info">&#9432;</span></h4>
                    <div class="col-md-12 rank"><?= isset($upper_department) ? $upper_department : "" ?></div>
					<!-- <div class="col-md-6 equal"><p>pokaali</p></div> -->
			</div>
			<div class="col-md-12 col-sm-12">
				<h4>{{trans('main.department_rank')}} <span id="info">&#9432;</span></h4>
				<div class="col-md-12 rank"><?= isset($department) ? $department : "" ?> </div>
				<!-- <div class="col-md-6 equal"><p>pokaali</p></div> -->
                    
			</div>
			<div class="col-md-12 col-sm-12">
				<?= isset($weekly_activity) ? $weekly_activity : "" ?>
			
			</div>
		</div>
        <div class="col-md-4 col-sm-6 border_left">
				
                <div class="col-md-12 col-sm-12">
                            <?= isset($accomplishment) ? $accomplishment : "" ?>
                </div>
                <div class="col-md-12 col-sm-12">
                </div>
            
        </div>
        
    </div>
</div>

<style>
    canvas{
        max-width:<?= $width ?>px;
        max-height:<?= $height ?>px;
    }
</style>
@endsection