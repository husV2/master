
<div class='friend_request_message'>
    <h5>{{trans('main.friend_request')}}</h5>
    <p>{{trans('main.friend_requests_from')}}</p>
    <?php foreach(Auth::user()->friendRequests as $futureFriend): ?>
    <div
       class='friend_request_block'
       data-toggle="tooltip" 
       title="<?= $futureFriend->firstname.' '.$futureFriend->lastname ?>">
    <a href='{{url('/profile/'.$futureFriend->id)}}'><?= $futureFriend->username ?></a>
    <a href='{{url('/accept/friendRequest/'.$futureFriend->id)}}'><button type='button' class='btn'><span class='glyphicon glyphicon-ok'></span></button></a>
    <a href='{{url('/decline/friendRequest/'.$futureFriend->id)}}'><button type='button' class='btn'><span class='glyphicon glyphicon-remove'></span></button></a>
    </div>
    <?php endforeach; ?>
            
</div>