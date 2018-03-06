<script src="{{ URL::asset('js/views/trainingbuddy.js') }}"></script>
<div id="info-container" class="col-sm-1 nopads">
	<div class="row">
		<?php if(isset($health_infos) && count($health_infos) > 0):?>
			<script>
				var infoBubbleTexts = JSON.parse('<?php echo json_encode($health_infos); ?>');
			</script>
			<div class="col-md-12 nopads">
			<div class="speech-bubble">
				<p class="sb-placeholder">. . .</p>
				<p id="infoText" class="sb-msg">Tähän tulee lyhyitä tekstejä terveydestä. Sisältö muuttuu x ajan kuluttua. Tämä on maksimi merkkimäärä.</p>
			</div>
		</div>
		<?php endif; ?>
		@if($survey_fillable)
		<div class="col-md-12 nopads">
			<p id="survey" class="survey">?</p>
		</div>
		@endif
		<?php if(isset($admin_notifications) && count($admin_notifications) > 0):?>
			<script>
				var adminNotifications = JSON.parse('<?php echo json_encode($admin_notifications); ?>');
			</script>
			<div class="col-md-12 nopads">
				<div class="info speech-bubble">
					<p class="sb-placeholder">!</p>
					<p id="notificationText" class="sb-msg">Tähän tulee lyhyitä tekstejä terveydestä. Sisältö muuttuu x ajan kuluttua. Tämä on maksimi merkkimäärä.</p>
				</div>
			</div>
		<?php endif; ?>
		
	</div>
</div>
<div class="col-sm-3">
    <img src="{{ URL::asset($image) }}" class="img-responsive" id="little_helper_home">
</div>

@if($survey_fillable)
	@include('partials.survey')
@endif