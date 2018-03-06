<div id="achievement-container">
    <img src="{{ URL::asset('img/achievement_star.svg') }}" alt="{{ trans('main.you_earned_achievement') }}" />
    <div id="achievement-popup">
        <h5 style="text-transform: uppercase">{{ trans('main.new_achievement') }}</h5>
        <h4 class="achievement-title">
            @if(session('achievement'))
            {{ session('achievement')[0]["name"] }}
            @elseif(!empty($achievement))
            {{ $achievement[0]["name"] }}
            @endif
        </h4>
        <p class="achievement-description">
            @if(session('achievement'))
            {{ session('achievement')[0]["description"] }}
            @elseif(!empty($achievement))
            {{ $achievement[0]["description"] }}
            @endif
        </p>
    </div>
</div>
@if(session('achievement') || !empty($achievement))
<script>
$(function(){
    achievementController.pop();
});
@endif
</script>


