<div class="container-fluid">
    <div class="row">
        <div class="col-md-12"><h3>{{trans('main.change_profile_settings')}}</h3></div>
        <div class='col-md-12 edit-section'>
            <?php if(isset($settings) && count($settings) > 0):?>
            <form method="post" id="update-form" action="{{url('edit/profile')}}" role="form">
                {{ csrf_field() }}
                    <?php foreach($settings as $key => $setting):?>
                        <div class="form-group {{ $errors->has($key) ? ' has-error' : '' }}">
                            @if ($errors->has($key))
                                <span class="help-block">
                                    <strong>{{ $errors->first($key) }}</strong>
                                </span>
                            @endif
                            <label for="<?=$key?>"><h4>{{trans('main.'.$key) }}</h4></label>
                            <input type="text" name="<?=$key?>" value="<?=$setting?>" class="form-control" />
                        </div>
                    <?php endforeach; ?>
                <button type="submit" class="btn btn-success update-btn">{{trans('main.save')}}</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>