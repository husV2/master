@extends('layouts.app')
@section('header')
<script>
    var level = 1;
//            $( document ).ajaxComplete(function( event, xhr, settings ){
//                if(level > 5)
//                {
//                    $('.organization_select').attr("disabled", false);
//                    return;
//                }
//                $('.organization_select[name="hus_group_'+level+'_id"] option[value="'+level+'_'+groups[level-1]+'"').prop('selected', true);
//                console.log(groups[level-1]+" "+level);
//                getNextLevel(groups[level-1], level+1);
//                level++;
//            });
            
            $(function(){
//                $('.organization_select').attr("disabled", true);
//                $('.organization_select[name="hus_group_1_id"] option[value="1_'+groups[0]+'"').prop('selected', true);
//                level++;
//                getNextLevel(groups[0], 2);
                
                $('.update-btn').click(function(e){
                    e.preventDefault();
                    $('.organization_select.hidden').remove();
                    $('.organization_select option:selected').each(function(i, item){
                        $(item).val($(item).val().split("_")[1]);
                    });
                    $("#update-form").submit();
                });
            });
            
</script>
<script src="{{ URL::asset('js/registrationOrganizationController.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('css/profile.css') }}">
@endsection
@section('content')
<div class="row">
    <div class="col-md-2 col-md-offset-3"><h2 id="info-title">{{trans('main.settings')}}</h2></div>
    <div class="col-md-2 col-md-offset-2"><h2 id="info-username">{{$settings["username"]}}</h2></div>
</div>
    <div class="row">
    <div class='col-md-6 col-md-offset-3 edit-section'>
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
                        
                        <?php if($key === "group_disabled"): ?>
                        
                        <select name="hus_group_1_id" class="organization_select form-control">
                            <option value="none" selected="selected" class="initial">{{ trans('auth.organization_choose') }}</option>
                            <?php foreach($data as $key => $or): ?>
                                    <option value="1_<?= $key ?>"><?=$or?></option>
                            <?php endforeach; ?>
                            <select>
                            <select name="hus_group_2_id" class="organization_select hidden form-control">
                                    <option value="none" selected="selected" class="initial">{{ trans('auth.organization_choose') }}</option>
                            <select>
                            <select name="hus_group_3_id" class="organization_select hidden form-control">
                                    <option value="none" selected="selected" class="initial">{{ trans('auth.organization_choose') }}</option>
                            <select>
                            <select name="hus_group_4_id" class="organization_select hidden form-control">
                                    <option value="none" selected="selected" class="initial">{{ trans('auth.organization_choose') }}</option>
                            <select>
                            <select name="hus_group_5_id" class="organization_select hidden form-control">
                                    <option value="none" selected="selected" class="initial">{{ trans('auth.organization_choose') }}</option>
                            <select>
                                
                        <?php elseif($key === "ex_program"): ?>
                                <select name="ex_program_fk" class="form-control program-chooser">
                                <?php foreach($programs as $program): ?>
                                        <option value="<?=$program->id?>"><?= $program->name ?></option>
                                <?php endforeach; ?>
                                </select>
                                
                        <?php else: ?>
                        <input type="text" name="<?=$key?>" value="<?=$setting?>" class="form-control" />
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <button type="button" class="btn btn-success update-btn">{{trans('main.save')}}</button>
            <a href="{{url()->previous()}}"><button type="button" class="btn btn-default">{{trans('main.back')}}</button></a>
        </form>
        <?php endif; ?>
    </div>
    <div class='col-md-2 col-sm-12'></div>
</div>
@endsection