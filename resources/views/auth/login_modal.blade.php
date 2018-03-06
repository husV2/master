<div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  </div>
		  <div class="modal-body">
			<div class="container-fluid bd-example-row">
                        <form class='register_form'>  
                          <p id="error-message"></p>
			  <div class="row reg1 register_tab">
				<div class="col-xs-7 col-md-7 input_block">
						<div class="form-group register">
							<div class="col-xs-11 col-sm-11">
								<p class="label-normal">{{ trans('auth.username') }}</p>
							</div>
							<div class="col-xs-11 col-sm-11">
                                        <input id="username" type="username" class="form-control i_reg" name="username" required value="{{ old('username') }}">
							</div>
							<div class="col-xs-1 col-sm-1 helpiconcol">
								<span class="info_sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="{{trans('main.username_help')}}">&#x24d8;</span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-xs-11 col-sm-11">
								<p class="label-normal">{{ trans('auth.password') }}</p>
							</div>
							<div class="col-xs-11 col-sm-11">
                                <input id="password1" type="password" class="form-control i_reg" required name="password">
							</div>
							<div class="col-xs-1 col-sm-1 helpiconcol">
								<span class="info_sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="{{trans('main.password_help')}}">&#x24d8;</span>
							</div>
						</div>
						<div class="form-group">
						<div class="col-xs-11 col-sm-11">
								<p class="label-normal">{{ trans('auth.confpass') }}</p>
						</div>
							<div class="col-xs-11 col-sm-11">
                                <input id="confirm_password" type="password" required class="form-control i_reg" name="password_confirmation">
							</div>
							<div class="col-xs-1 col-sm-1 helpiconcol">
								<span class="info_sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="{{trans('main.password_confirm_help')}}">&#x24d8;</span>
							</div>
						</div>
						<div class="form-group">
						<div class="col-xs-11 col-sm-11">
								<p class="label-normal">{{ trans('auth.email') }}</p>
						</div>
							<div class="col-xs-11 col-sm-11">
                                <input id="email" type="email" required class="form-control i_reg" name="email">
							</div>
							<div class="col-xs-1 col-sm-1 helpiconcol">
								<span class="info_sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="{{trans('main.email_help')}}">&#x24d8;</span>
							</div>
						</div>
				</div>
				<div class="col-xs-5 col-md-5 text_block" id="apurigif_modal">
					<img src="{{ URL::asset('img/apuri_welcome.gif') }}" alt="Image not found" class="img-responsive helper_welcome">
				</div>
			  </div>
                        </form>
                        <form class='register_form'>
			  <div class="row reg2 register_tab" style="display:none;">
				<div class="col-xs-7 col-md-7 input_block">
					<div class="col-sm-12 input_block_label">
						<p class="label-normal">{{ trans('auth.background_information') }}</p>
					</div>
						<div class="form-group">
							<div class="col-xs-11 col-sm-11">
								<p class="label-normal">{{ trans('auth.organization') }}</p>
							</div>
							<div class="col-xs-11 col-sm-11">
								<select name="group1" required class="organization_select">
									<option value="none" selected="selected" class="initial">{{ trans('auth.organization_choose') }}</option>
									<?php foreach($data as $key => $or): ?>
										<option value="1_<?= $key ?>"><?=$or?></option>
									<?php endforeach; ?>
								<select>
								<select name="group2" required class="organization_select hidden">
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

							</div>
							<div class="col-xs-1 col-sm-1 helpiconcol">
								<span class="info_sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="{{trans('main.organization_help')}}">&#x24d8;</span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-x-11 col-sm-11">
								<p class="label-normal">{{ trans('main.workhours') }}</p>
							</div>
							<div class="col-xs-11 col-sm-11">
                                <input type="text" class="form-control i_reg" name="workhour">
							</div>
							<div class="col-xs-1 col-sm-1 helpiconcol">
								<span class="info_sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="{{trans('main.workhour_help')}}">&#x24d8;</span>
							</div>
						</div>
				</div>
				<div class="col-xs-5 col-md-5 text_block">
					<img src="{{ URL::asset('img/apuri_welcome.gif') }}" alt="Image not found" class="img-responsive helper_welcome">
				</div>
			  </div>
                        </form>
                        <form class='register_form'>
			  <div class="row reg3 register_tab" style="display:none;">
				<div class="col-xs-7 col-md-7 input_block">
					<div class="col-sm-12 input_block_label">
						<p class="label-normal">{{ trans('auth.customization') }}</p>
					</div>
                                    <!-- !!!!!!! Aktivoidaan tämä kun implementointi on valmis !!!!!!! ->
<!--						<div class="form-group">
							<div class="col-sm-11">
								<p class="label-normal">Valitse käyttöliittymän väri</p>
							</div>
							<div id="select-theme" class="col-sm-11">
								<div id="theme_scarab" class="col-sm-2 colorbox color_scarab"></div>
								<div id="theme_blue" class="col-sm-2 colorbox color_blue"></div>
								<div id="theme_purple" class="col-sm-2 colorbox color_purple"></div>
								<div id="theme_green" class="col-sm-2 colorbox color_green"></div>
								<div id="theme_pink" class="col-sm-2 colorbox color_pink"></div>
							</div>
							<input id="theme" name="theme" hidden>
						</div>-->
						<div class="form-group">
							<div class="col-xs-11 col-sm-11">
								<p class="label-normal">Lisää mottosi</p>
							</div>
							<div class="col-xs-11 col-sm-11">
                                <input id="motto" type="text" class="form-control i_reg" name="motto">
							</div>
						</div>
				</div>
				<div class="col-xs-5 col-md-5 text_block">
					<img src="{{ URL::asset('img/apuri_welcome.gif') }}" class="img-responsive helper_welcome">
					<p class="text-normal"></p>
				</div>
			  </div>
                        </form>
                          <div class="row reg4 register_tab" style="display:none; text-align: center;">
                              <div class="col-md-12">
                                  <h4>{{trans('auth.errors')}}</h4>
                                  <button type="button" class="btn_back">{{ trans('main.back') }}</button>
                              </div>
                          </div>
                            <div class="row reg6 register_tab" style="display:none; text-align: center;">
                              <div class="col-md-12">
                                  <h4>{{trans('auth.thank_you')}}</h4>
                                  <p>{{trans('auth.redirected')}}</p>
                              </div>
                          </div>
			</div>
		  </div>
		  <div class="modal-footer">
                        <p id="checkMessage"></p>
			<button type="button" data-page="1" class="btn_register">{{ trans('main.next') }}</button>
			<span id="btn_register_icon" class="glyphicon glyphicon-play-circle" aria-hidden="true"></span>
		  </div>
		</div>
	</div>
</div>
