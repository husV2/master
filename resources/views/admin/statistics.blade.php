@extends('layouts.app')

@section('content')
<div class="col-md-4 col-xs-12">
    <h3>{{trans('main.exerciseCountByGroup')}}</h3>
    @if(isset($exerciseCounts))
        @foreach($exerciseCounts as $level => $subs)
            <h4>{{trans('main.level')." ".$level}}</h4>
            @foreach($subs as $sub)
            <p style="font-size: {{14*(1 + $sub["value"]/100)}}px">{{$sub["name"]}}: {{$sub["value"]}}</p>
            @endforeach
        @endforeach
    @endif
</div>
    
@endsection
