<script>
var submitSurveyUrl = "{{ url('survey/submit') }}";
</script>
<script src="{{ URL::asset('js/views/survey.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('css/survey.css') }}">
<style>
	.vitality-1 {
		background-image: url({{ URL::asset('img/survey/vitality-1.jpg') }})
	}
	.vitality-2 {
		background-image: url({{URL::asset('img/survey/vitality-2.jpg') }})
	}
	.vitality-3 {
		background-image: url({{ URL::asset('img/survey/vitality-3.jpg') }})
	}
	.vitality-4 {
		background-image: url({{ URL::asset('img/survey/vitality-4.jpg') }})
	}
	.vitality-5 {
		background-image: url({{ URL::asset('img/survey/vitality-5.jpg') }})
	}
	
	.vitality-1.active {
		background-image: url({{ URL::asset('img/survey/vitality-1-a.jpg') }})
	}
	.vitality-2.active {
		background-image: url({{ URL::asset('img/survey/vitality-2-a.jpg') }})
	}
	.vitality-3.active {
		background-image: url({{ URL::asset('img/survey/vitality-3-a.jpg') }})
	}
	.vitality-4.active {
		background-image: url({{ URL::asset('img/survey/vitality-4-a.jpg') }})
	}
	.vitality-5.active {
		background-image: url({{ URL::asset('img/survey/vitality-5-a.jpg') }})
	}
</style>
<div class="modal fade bd-example-modal-lg" id="survey-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<div class="col-sm-10">
				<p class="mod_title">Vireys kysely</p>
			</div>
		</div>
		<div class="modal-body">
			<div class="row"> <!-- Vireyskyselyn kuvaus -->
				<div class="col-sm-12">
					<p class="survey-text">Vireyskyselyllä pystyt seuraamaan HUS Minitreeniohjelman käytön vaikuttavuutta työssä jaksamiseen. Voit tehdä testin kun itsellesi parhaiten sopii.</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="vitalities">
						<div class="vitality-1" data-value=1></div>
						<div class="vitality-2" data-value=2></div>
						<div class="vitality-3" data-value=3></div>
						<div class="vitality-4" data-value=4></div>
						<div class="vitality-5" data-value=5></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<p id="vitalityDescription" class="survey-text description">Valitse naama, joka kuvaa tämän hetkistä vireyttäsi.</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-3 col-sm-push-9">
					<button id="saveSurvey" type="button" class="survey-button">Tallenna vastaus</button>
				</div>
				<div class="col-sm-2 col-sm-push-4">
					<button type="button" class="survey-button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">Myöhemmin</span>
					</button>
				</div>
			</div>
		</div>
	</div>
  </div>
</div>