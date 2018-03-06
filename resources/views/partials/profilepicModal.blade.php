<div class="modal fade" id="profilepic-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
          <form method="post" action="{{url('edit/profilepic')}}" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="form-group">
                    @if ($errors->has('profilepic'))
                        <span class="help-block">
                            <strong>{{ $errors->first('profilepic') }}</strong>
                        </span>
                    @endif
                    <label for="profilepic">{{trans('main.profilepic')}}</label>
                    <p class="help-block">{{trans('admin.exercise_image_help')}}</p>
                    <input type="file" class="form-control" name="profilepic" value="">
              </div>
              <button type="submit" class="btn btn-success">{{trans('main.save')}}</button>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('main.close')}}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
