@extends('layouts.app')

@section('content')
<div class="col-md-12 col-sm-12 col-lg-12">
    <ul class="list-group">
        <a class="list-group-item" href='{{url('/admin/exercise/edit')}}'>{{trans('admin.new_exercise')}}</a>
    </ul>
    <h5>{{ trans('main.or') }}</h5>
    <h4>{{ trans('admin.modify_exercises') }}:</h4>
    <ul class="list-group">
      <?php if(isset($exercises) && count($exercises) > 0):?>
        <?php foreach($exercises as $ex): ?>
            <a class="list-group-item" href='{{url('/admin/exercise/edit')}}/{{ $ex->id }}'>{{ $ex->name }}  -  {{ trans('main.lastModified') }}: {{ date("d.m.Y", strtotime($ex->updated_at)) }}</a>
        <?php endforeach ?>
      <?php else: ?>
            <h5 class="list-group-item">{{ trans('admin.noExercises') }}</h5>
      <?php endif; ?>
    </ul>   
</div>
@endsection

