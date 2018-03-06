<script>    
var nextUrl = "{{ url('get/next') }}";
var completeUrl = "{{ url('get/complete') }}";
var snoozeUrl = "{{ url('get/snooze') }}";
var skipUrl = "{{ url('get/skip') }}";
var soundUrl = "{{ URL::asset('audio/notifications/notification1.mp3') }}";
var exCompletedSoundUrl = "{{ URL::asset('audio/notifications/notification3.mp3') }}";
</script>

<script src="{{ URL::asset('js/jquery.countdown.min.js') }}"></script>
<script src="{{ URL::asset('js/views/countdown.js') }}"></script>

<!-- Treenin suoritus popup, ilmestyy kun ajastin == 0 -->		
<div class="modal fade bd-example-modal-lg" id="modtest" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<div class="col-sm-12"><p id="ex-name" class="mod_title"></p></div>
			<!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
		</div>
		<div class="modal-body">
			<div class="row" id="nomargin">
				<div class="col-sm-7">
					<div class="col-sm-10 col-sm-offset-2">
						<div id="ex-content">
							<!-- Treenin html koodi tulee tähän -->
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="mod_button_placement">
									<button type="button" class="btn_timer btn btn-md" id="cd-decline">Hylkää</button>
									<button type="button" class="btn_timer btn btn-md" id="ex-completed" disabled>Valmis</button>
								</div>
								<p id="ex-completed-cd"></p>
								<input type="checkbox" id="ex-completed-notification"><label>Ilmoitusääni pois/päälle</label>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="col-sm-12">
						<img src="{{ URL::asset('img/apuri_muskeli.gif') }}" class="img-responsive little_helper_modal">
					</div>
				</div>
			</div>
		</div>
	</div>
  </div>
</div>