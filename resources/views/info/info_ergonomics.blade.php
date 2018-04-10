@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6 col-sm-6"><h2 id="info-title">{{trans('main.ergonomics')}}</h2></div>
        <div class="col-md-2 col-sm-6"><h2 id="info-username">{{$settings["username"]}}</h2></div>
    </div>
    <div class="col-md-12 col-sm-12 col-lg-12">

    </div>
@endsection