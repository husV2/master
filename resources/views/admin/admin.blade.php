@extends('layouts.app')

@section('content')
<div class="col-md-12 col-sm-12 col-lg-12">
    <ul class="list-group">
        <a class="list-group-item" href="{{url('/admin/exercise')}}">Hallinnoi harjoituksia</a>
        <a class="list-group-item" href="{{url('/admin/exprogram')}}">Hallinnoi harjoitusohjelmia</a>
        <a class="list-group-item" href="{{url('/admin/healthinfo')}}">Hallinnoi terveysinfoja</a>
        <a class="list-group-item" href="{{url('/admin/notification')}}">Hallinnoi ilmoituksia</a>
        <a class="list-group-item" href="{{url('/admin/statistics')}}">Statistiikkaa</a>
    </ul>
</div>
@endsection