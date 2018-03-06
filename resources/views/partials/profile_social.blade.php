<?php
    $friendCount = !empty($friends) ? count($friends) : 0;
    $class = empty($guest) ? 'col-md-6' : 'col-md-12';
?>
<div class="container-fluid">
    <div class="row">
        <div class="{{ $class }} col-sm-12">
            @if(empty($guest))
            <p class="lead">{{trans('main.send_friends_message')}}</p>
            @else
            <p class="lead">{{trans('main.send_friends_message_guest')}}</p>
            @endif
            <div class="friends-container">
            @if(!empty($friends) && $friendCount > 0)
                @foreach($friends as $friend)
                    <div class="friend_box">
                        <a href="{{url('/profile/'.$friend["id"])}}"><img src="{{ URL::asset('storage/avatars/'.$friend["img"]) }}" data-toggle="tooltip" data-placement="bottom" title="{{ $friend["name"] }}" class="friend_image" /></a>
                    </div>
                @endforeach
            @elseif($friendCount < 1)
            <small>{{ trans('main.no_friends') }}</small>
            @endif
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            @if(empty($guest) && !empty($grid))
            <div class="form-group has-feedback has-feedback-left">
                <input class="form-control find_friends" type="text" placeholder="{{trans('main.search_friends')}}" />
                <i class="form-control-feedback glyphicon glyphicon-search"></i>
            </div>
            <?= $grid ?>
            @endif
        </div>
    </div>
</div>