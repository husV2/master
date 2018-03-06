@extends('layouts.app')

@section('content')
<div class="col-md-12 col-sm-12 col-lg-12">
    <ul class="list-group">
        <a class="list-group-item" href="{{url('/admin/notification/edit')}}">Luo uusi ilmoitus</a>
    </ul>
    <h5>{{ trans('main.or') }}</h5>
    <h4>Muokkaa aktiivisia ilmoituksia:</h4>
    <ul class="list-group">
      <?php if(isset($notifications) && count($notifications) > 0):?>
        <?php foreach($notifications as $n): ?>
            <a class="list-group-item" href='{{url('/admin/notification/edit')}}/{{ $n->id }}'>{{ $n->message }}  -  {{ trans('main.lastModified') }}: {{ date("d.m.Y", strtotime($n->updated_at)) }}</a>
        <?php endforeach ?>
      <?php else: ?>
            <h5 class="list-group-item">Ei ole luotu ilmoituksia</h5>
      <?php endif; ?>
    </ul>   
</div>
@endsection