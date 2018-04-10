@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row" id="nomargin">
		<div class="col-sm-8 nopads">
			<div class="col-sm-12 equalize">
                            <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                            </svg>
                            <div class="col-sm-1 col-l-btn equal nopads"><div class="btn_placement"><button id="btn_prev"><span class="glyphicon glyphicon-menu-left"></span></button></div></div>
                            <div class="col-sm-10 equal nopads" id="calendar"><a href="#" class="link_modify" onClick="calendar_modify()">{{trans('main.modify_calendar')}}<span class="glyphicon glyphicon-chevron-right"></span></a></div>
                            <div class="col-sm-1 col-r-btn equal nopads"><div class="btn_placement"><button id="btn_next"><span class="glyphicon glyphicon-menu-right"></span></button></div></div>
			</div>
		</div>
        <div class="col-md-offset-1">
            <?= Auth::user()->buddy->view(); ?>
        </div>
	</div>
</div>
<?= isset($modal) ? $modal : "" ?>
<?= isset($settings_modal) ? $settings_modal : "" ?>
@endsection