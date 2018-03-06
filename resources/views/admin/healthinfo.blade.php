@extends('layouts.app')

@section('content')
<div class="col-md-12 col-sm-12 col-lg-12">
    <ul class="list-group">
        <a class="list-group-item" href="{{url('/admin/healthinfo/edit')}}">Luo uusi terveysinfo</a>
    </ul>
    <h5>{{ trans('main.or') }}</h5>
    <h4>Muokkaa terveysinfoja:</h4>
    <ul class="list-group">
      <?php if(isset($health_infos) && count($health_infos) > 0):?>
        <?php foreach($health_infos as $hi): ?>
            <a class="list-group-item" href='{{url('/admin/healthinfo/edit')}}/{{ $hi->id }}'>{{ $hi->message }}  -  {{ trans('main.lastModified') }}: {{ date("d.m.Y", strtotime($hi->updated_at)) }}</a>
        <?php endforeach ?>
      <?php else: ?>
            <h5 class="list-group-item">Ei ole luotu terveysinfoja</h5>
      <?php endif; ?>
    </ul>   
</div>
@endsection