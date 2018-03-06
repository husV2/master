@extends('layouts.app')

@section('content')
<div class="col-md-12 col-sm-12 col-lg-12">

<div style="margin: 0 auto; padding-bottom: 20px;" class="col-md-8">
<?php if(isset($categories) && count($categories) > 0): ?>    
    <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ url('/new/exercise') }}">
        {{ csrf_field() }}
        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
          <label for="name">{{trans('admin.exercise_name')}}</label>
          <input type="text" class="form-control" name="name" value="<?= (isset($ex) && $ex !== null) ? $ex->name : old('name') ?>" required>
        </div>
        <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
            @if ($errors->has('description'))
                <span class="help-block">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
          <label for="description">{{trans('admin.exercise_description')}}</label>
          <textarea rows="4" cols="50" class="form-control" name="description"><?= (isset($ex) && $ex !== null) ? $ex->description : old('description') ?></textarea>
        </div>
        <div class="form-group {{ $errors->has('duration') ? ' has-error' : '' }}">
            @if ($errors->has('duration'))
                <span class="help-block">
                    <strong>{{ $errors->first('duration') }}</strong>
                </span>
            @endif
          <label for="duration">{{trans('admin.exercise_duration')}}</label>
          <p class="help-block">{{trans('admin.exercise_duration_help')}}</p>
          <input type="number" class="form-control" name="duration" value="<?= (isset($ex) && $ex !== null) ? $ex->duration : old('duration') ?>" required>
        </div>
        <div class="form-group {{ $errors->has('count') ? ' has-error' : '' }}">
            @if ($errors->has('duration'))
                <span class="help-block">
                    <strong>{{ $errors->first('count') }}</strong>
                </span>
            @endif
          <label for="duration">{{trans('admin.exercise_count')}}</label>
          <input type="number" class="form-control" name="count" value="<?= (isset($ex) && $ex !== null) ? $ex->count : old('count') ?>" required>
        </div>
        <div class="form-group {{ $errors->has('category') ? ' has-error' : '' }}">
            @if ($errors->has('category'))
                <span class="help-block">
                    <strong>{{ $errors->first('category') }}</strong>
                </span>
            @endif
          <label for="category">{{trans('admin.exercise_category')}} *</label>
          <select name="category" class="form-control">
              <option selected="selected">{{trans('admin.exercise_category_choose')}}</option>
              <?php foreach($categories as $cat): ?>
              <option style="color:{{$cat->color}}" value="{{$cat->id}}">{{$cat->name}}</option>
              <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group {{ $errors->has('text') ? ' has-error' : '' }}">
            @if ($errors->has('text'))
                <span class="help-block">
                    <strong>{{ $errors->first('text') }}</strong>
                </span>
            @endif
          <label for="text">{{trans('admin.exercise_text')}}</label>
          <textarea required rows="4" cols="50" class="form-control" name="text"><?= (isset($ex) && $ex !== null) ? $ex->text : old('text') ?></textarea>
        </div>
        <div class="form-group {{ $errors->has('video') ? ' has-error' : '' }}">
            @if ($errors->has('video'))
                <span class="help-block">
                    <strong>{{ $errors->first('video') }}</strong>
                </span>
            @endif
          <label for="video">{{trans('admin.exercise_video')}}</label>
          <p class="help-block">{{trans('admin.exercise_video_help')}}</p>
          <input type="text" class="form-control" name="video" value="<?= (isset($ex) && $ex !== null) ? $ex->video : old('video') ?>">
        </div>
        <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
            @if ($errors->has('image'))
                <span class="help-block">
                    <strong>{{ $errors->first('image') }}</strong>
                </span>
            @endif
          <label for="image">{{trans('admin.exercise_image')}}</label>
          <p class="help-block">{{trans('admin.exercise_image_help')}}</p>
          <?php if(isset($ex->image) && $ex->image !== ""): ?>
            <img src="{{URL::asset('storage/exercises/images/'.$ex->image)}}" class="img-responsive" style="max-width: 100px; max-height:100px" />
          <?php endif; ?>
          <input type="file" class="form-control" name="image" value="{{old('image')}}">
        </div>
        <input type="hidden" name="id" value="<?= (isset($ex) && $ex !== null) ? $ex->id : -1 ?>" />
        <div class="form-group">
          <label for="audio">{{trans('admin.exercise_audio')}}</label>
          <p class="help-block">{{trans('admin.exercise_audio_help')}}</p>
          <p></p>
          <input type="file" class="form-control" name="audio">
        </div>
        <button type="submit" class="btn btn-success">{{trans('main.save')}}</button><a href="{{ url('admin/notification') }}"><button type="button" class="btn btn-default">{{trans('main.back')}}</button></a>
    </form>
</div>
<?php endif; ?>
<script>
    $(function(){
        
        $('input:required, textarea:required').each(function(i, item){
             var label = $('label[for="'+$(item).attr('id')+'"]');
            label.text(label.text()+" *");
        });
        
        <?php if(isset($ex) && $ex !== null):?>
            $('option[value="<?= $ex->ex_category_fk ?>"').attr("selected", true);
        <?php endif; ?>
    });
    
</script>
</div>
@endsection