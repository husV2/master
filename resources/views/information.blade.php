@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-5 col-sm-5 col-lg-5"><h2 id="info-title">{{trans('main.info')}}</h2></div>
        <div class="col-md-1 col-sm-1 col-lg-1"><h2 id="info-username">{{$settings["username"]}}</h2></div>
    </div>
    <div class="col-md-7 col-sm-7 col-lg-7">
        <ul class="list-group">
            <a class="list-group-item" href="{{url('/info/ergonomics')}}">Katso työergonomiaohjeet</a>
            <a class="list-group-item" href="{{url('/info/security')}}">Katso tietoturvaselvitys</a>
        </ul>
    </div>
@endsection