@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">

                <div class="panel-body" style="text-align: center">
                    <h5>{{$data["msg"]}}</h5>
                    </br>
                    @if (!$data["mailSent"])
                    <form role="form" action="{{ url('/register/sendnewcode') }}" method="GET">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-default">
                            <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
