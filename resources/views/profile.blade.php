@extends('layouts.app', isset($achievement) ? ['achievement' => $achievement] : array())
@section('header')
<script>
//    $(function(){
//        $('.setting_box #workhours').text($('.setting_box #workhours').text()+" h");
//        $('.setting_box #event_interval').text($('.setting_box #event_interval').text()+" min");
//
//        $('#nav-tabs a').click(function (e) {
//            e.preventDefault();
//            $(this).tab('show');
//            $(this).removeClass("new-messages");
//            $('#tab-title').text($(this).find("img").attr("title"));
//          });
//    });
var routes ={
    "update_chat": "{{ url('update/chat') }}",
    "check_missed": "{{ url('get/chat/lastID') }}",
    "send_message": "{{ url('message/send') }}" ,
    "remove_message": "{{ url('message/remove') }}",
    "confirm_old": "{{ url('password/confirm') }}",
    "change_pw": "{{ url('password/change') }}"
};
</script>
<link rel="stylesheet" href="{{ URL::asset('css/profile.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/grid.css') }}">
<script src="{{URL::asset('js/chart.bundle.min.js')}}"></script>
<script src="{{URL::asset('js/colorLuminance.js')}}"></script>
<script src="{{URL::asset('js/searchWindowProfile.js')}}"></script>
<script src="{{URL::asset('js/profileNavigation.js')}}"></script>
<script src="{{URL::asset('js/passwordChanger.js') }}"></script>
@endsection
@section('content')
<?= isset($modal) ? $modal : "" ?>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-sm-6"><h2 id="tab-title">{{trans('main.profile')}}</h2></div>
        <div class="col-md-2 col-sm-6"><h2 id="tab-username">{{$settings["username"]}}</h2></div>
    </div>
<div class="row" >
    <div class="col-md-8 col-sm-12">
        <div class="tabbable tabs-left">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><img class="img-responsive" src="{{URL::asset('img/tabi_profiili.png')}}" title="{{trans('main.profile')}}"/></a></li>
              <li role="presentation"><a href="#messages" aria-controls="social" role="tab" data-toggle="tab"><img class="img-responsive" src="{{URL::asset('img/tabi_viestit.png')}}" title="{{ trans('main.messages') }}"/></a></li>
              <li role="presentation"><a href="#social" aria-controls="social" role="tab" data-toggle="tab"><img class="img-responsive" src="{{URL::asset('img/tabi_kaverit.png')}}" title="{{ trans('main.friends') }}"/></a></li>
              <li role="presentation"><a href="#charts" aria-controls="charts" role="tab" data-toggle="tab"><img class="img-responsive" src="{{URL::asset('img/tabi_saavutukset.png')}}" title="{{ trans('main.accomplishments') }}"/></a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active fade in" id="profile">
                    <div>
                        <?php if(isset($settings) && count($settings) > 0):?>
                                <?php foreach($settings as $key => $setting):?>
                                    <div class="setting_box">
                                        <h4>{{trans('main.'.$key) }}</h4>
                                        <p id="{{$key}}">{{$setting}}</p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php if(!isset($guest)):?>
                            <a role="tab" data-toggle="tab" href="#settings" class="link_modify settings-change">{{trans('main.modify')}}<span class="glyphicon glyphicon-chevron-right"></span></a>
                            <a role="tab" data-toggle="tab" href="#password" class="link_modify password-change">{{trans('main.change_password')}}<span class="glyphicon glyphicon-chevron-right"></span></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="charts">
                   @include('partials.profile_charts', ['chart' => $chart, 'login_streak' => $login_streak, 'achievements' => $achievements, 'guest' => isset($guest) ? $guest : null])
                </div>
                <div role="tabpanel" class="tab-pane fade" id="messages">
                    <?= isset($messageBoard) ? $messageBoard : "" ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="social">
                    @include('partials.profile_social', ['friends' => $friends, 'guest' => isset($guest) ? $guest : null, 'grid' => isset($grid) ? $grid : null])
                </div>
                <div role="tabpanel" class="tab-pane fade" id="settings">
                    <?= isset($settings_tab) ? $settings_tab : "" ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="password">
                    <?= isset($password_tab) ? $password_tab : "" ?>
                </div>
            </div>
        </div>
    </div>
    <?= Auth::user()->buddy->view(); ?>
</div>
</div>

@endsection
