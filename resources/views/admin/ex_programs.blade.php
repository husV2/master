@extends('layouts.app')

@section('content')
<div class="col-md-12 col-sm-12 col-lg-12">
    <ul class="list-group">
        <a class="list-group-item" href="{{url('/admin/exprogram/edit')}}">Luo uusi treeniohjelma</a>
    </ul>
    <h5>{{ trans('main.or') }}</h5>
    <h4>Muokkaa treeniohjelmia:</h4>
    <ul class="list-group">
      <?php if(isset($exprograms) && count($exprograms) > 0):?>
        <?php foreach($exprograms as $ex): ?>
            <a class="list-group-item" href='{{url('/admin/exprogram/edit')}}/{{ $ex->id }}'>{{ $ex->name }}  -  {{ trans('main.lastModified') }}: {{ date("d.m.Y", strtotime($ex->updated_at)) }}</a>
        <?php endforeach ?>
      <?php else: ?>
            <h5 class="list-group-item">Ei ole luotu treeniohjelmia</h5>
      <?php endif; ?>
    </ul>   
</div>
@endsection