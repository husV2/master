<?php 
    $isGuest = !empty($guest);
    $recordNum = isset($login_streak["best"]) ? $login_streak["best"] : 1;
    $txt = $isGuest ? trans('main.guest_login_record') : trans('main.login_record');
    $record = sprintf($txt, '<span class="colored">', $recordNum, '</span>');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-xs-12 nopads">
            <?= isset($chart) ? $chart : "" ?>
            <div class="achievements">
                @if(isset($achievements))
                    @foreach($achievements as $a)
                    <?php 
                        $incomplete = array_key_exists("count", $a) && !empty($a["count"]) && $a["count"] < $a["max"];
                        if($incomplete){ 
                            $percentage = 100 - (($a["count"] / $a["max"]) * 100); 
                            $num = $a["count"] .'/'. $a["max"]; 
                        }
                    ?>
                        @if(!empty($a["img"]))
                            <!--<div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 grid-image">-->
                            <div class="ach-img-container magnify" title="{{$incomplete ? ($a["title"]." - ".$num) : $a["desc"]}}" style="background-image:url({{$a["img"]}}); {{$incomplete ? "-webkit-filter: sepia(".$percentage."%); filter: sepia(".$percentage."%);" : ""}}">
<!--                                <img class="img-responsive" title="{{$a["title"]}}" src="{{$a["img"]}}"></img>-->
                                @if($incomplete)
                                <span>{{$num}}</span>
                                @endif
                            </div>
                            <!--</div>-->
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-md-6 col-xs-12 nopads">
            <p class="text-uppercase">{{ $isGuest ? trans('main.guest_consecutive_logins') : trans('main.consecutive_logins') }}</p>
            <h2 class="login-streak">{{ isset($login_streak["streak"]) ? $login_streak["streak"] : 1 }} <span class="text-uppercase login-streak-text">{{ trans('main.consecutive_days') }}</span></h2>
            <small>{!! $record !!}</small>
        </div>
    </div>
</div>