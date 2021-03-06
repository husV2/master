@extends('layouts.app')

@section('content')



<form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ url('save/healthinfo') }}">
	{{ csrf_field() }}
	<div class="form-group">
	  <label for="message">Ilmoituksen viesti</label>
	  <textarea id="messageArea" rows="4" cols="50" class="form-control" name="message" required><?= (isset($healthinfo) && $healthinfo !== null) ? $healthinfo->message : old('message') ?></textarea>
	  <p id="charCounterText"></p>
	</div>
	<div class="form-group">
		<label for="message">On aktiivinen</label>
		<input name="is_active" type="checkbox" <?= (isset($healthinfo) && $healthinfo->is_active == 1) ? 'checked' : '' ?>>
	</div>
	<input type="hidden" name="id" value="<?= (isset($healthinfo) && $healthinfo !== null) ? $healthinfo->id : -1 ?>" />
	<button type="submit" class="btn btn-success">{{trans('main.save')}}</button><a href="{{ url('admin/healthinfo') }}"><button type="button" class="btn btn-default">{{trans('main.back')}}</button></a>
</form>

<script>
	function charCounter() {
		var val = this;
        var len = val.value.length;
        if (len > 100) {
			val.value = val.value.substring(0, 100);
        }
		else {
			$('#charCounterText').html('Merkkejä jäljellä: ' + (100 - len));
		}
      };
	$("#messageArea").on('change keyup', charCounter);
</script>
@endsection