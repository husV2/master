<?php
   $count = isset($messages) ? $messages->count() : 0;
   $user = Auth::user();
?>
<div class="message_board">
    <div class="message-area">
        <div class="list-group messages">
            <?php if(isset($messages) && $count > 0): ?>
            <?php foreach($messages as $message): ?>
                <div class="list-group-item clearfix" data-id="<?= $message->id ?>">
                    <div class="message-board-avatar">
                        <a href="{{ url('/profile/'.$message->writer_id) }}">
                            <img title="{{ $message->username }}" src="{{ $message->avatar }}" class="img-responsive" />
                        </a>
                    </div>
                    <?php if($message->writer_id === $user->id || !isset($guest)): ?>
                    <span class="remove-message-icon" title="{{ trans('main.remove_message') }}">╳</span>
                    <?php endif;?>
                    <small class="list-group-item-heading">{{ $message->created_at }}</small>
                    <p class="list-group-item-text">{{ $message->message }}</p>
                </div>
            <?php endforeach; ?>
            <?php elseif($count < 1): ?>
            <p>{{ trans('main.no_messages') }}</p>
            <?php endif; ?>
        </div>
    </div>
    <?php if(!isset($guest) || $user->isFriendOf($owner)): ?>
    <div class="writing-area">
        <div class="form-group">
            <textarea class="form-control" id="chat-msg" type="text" placeholder="{{ trans('main.write_message') }}"></textarea>
            <p>{{ trans('main.characters_left') }} <span id="chars">255</span></p>
        </div>
        <div class="form-group">
            <button class="btn btn-default msg-board-btn">{{ trans('main.send') }}</button>
        </div>
    </div>
    <?php endif; ?>
<script> 
    var warning_message = "{{ trans('main.cantSendEmpty') }}";
    var user = "{{ isset($guest) ? $guest : Auth::user()->id }}";
</script>
<script src="{{ URL::asset('js/messageBoard.js') }}"></script>
</div>

