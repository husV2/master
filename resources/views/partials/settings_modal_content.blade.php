<script>
    $(function(){

        $('.program-chooser option[value="{{$userProgram}}"').prop('selected', true);
        $('.interval-chooser option[value="{{$settings["event_interval"]}}"').prop('selected', true);

    });
            
</script>
<form method='post' action="{{url('edit/calendar')}}" role="form">
    {{ csrf_field() }}
    @foreach($settings as $key => $setting)
        <div class="form-group {{ $errors->has($key) ? ' has-error' : '' }}">
            @if ($errors->has($key))
                <span class="help-block">
                    <strong>{{ $errors->first($key) }}</strong>
                </span>
            @endif
            <label for="{{$key}}"><h4>{{trans('main.'.$key) }}</h4></label>

            @if($key === "ex_program")
                    <select name="ex_program_fk" class="form-control program-chooser">
                    @foreach($programs as $program)
                            <option value="{{$program->id}}">{{$program->name}}</option>
                    @endforeach
                    </select>

            @elseif($key === 'event_interval')
            <select type="number" name="{{$key}}" class="form-control interval-chooser" />
                <option value='1'>1 min</option>
                <option value='5'>5 min</option>
                <option value='10'>10 min</option>
                <option value='15'>15 min</option>
                <option value='20'>20 min</option>
                <option value='25'>25 min</option>
                <option value='30'>30 min</option>
                <option value='35'>35 min</option>
                <option value='40'>40 min</option>
                <option value='45'>45 min</option>
                <option value='50'>50 min</option>
                <option value='60'>1 h</option>
                <option value='90'>1,5 h</option>
                <option value='120'>2 h</option>
                <option value='150'>2,5 h</option>
                <option value='180'>3 h</option>
            </select>
            
            @elseif($key === 'generateRandom')
            <div class="checkbox">
                <label><input type="checkbox" value="1" name="{{$key}}" <?= $setting ? "checked" : "" ?>>{{trans('main.generateRandom_choose')}}</label>
                <span data-toggle="tooltip" title="{{trans('main.generateRandom_info')}}" id="info">&#9432;</span>
            </div>
            @endif
            
        </div>
    @endforeach
    <button type="submit" class="btn btn-success save-btn">{{trans('main.save')}}</button>
</form>