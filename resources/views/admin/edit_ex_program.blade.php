@extends('layouts.app')

@section('header')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{ URL::asset('css/exprogram.css') }}">

<script>    
var getExUrl = "{{ url('get/next') }}";
</script>

@endsection

@section('content')
<div class="row">
	<div class="col-md-10">
		<h3>Hae Treenejä</h3>
	</div>
	<div class="col-md-2">
		<a href="{{ url('admin/exprogram') }}"><button type="button" class="btn btn-default">{{trans('main.back')}}</button></a>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<form id="ex-filter" class="form-inline">
			<div class="form-group">
				<label class="control-label">Kategoria</label>
				<select name="excategory">
					<option value="">Kaikki</option>
					@foreach($categories as $cat)
						<option value="{{ $cat->id }}" style="background-color: {{ $cat->color }}">{{ $cat->name }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group">
				<label>Treenin nimi</label>
				<input type="text" name="exname"/>
			</div>
			<input type="submit" value="Hae"/>
		</form>
	</div>
</div>	
<div class="row">
	<div class="col-md-12">
		<ul id="ex-list">
			@foreach($exercises as $ex)
				<li data-id="{{$ex->id}}" class="ui-state-highlight draggable" data-toggle="tooltip" title="{{ $ex->description }}" 
					data-exname="{{ $ex->name }}"
					data-excategory="{{ $ex->ex_category_fk }}"
					style="background-color: {{ $colors[$ex->ex_category_fk] }}">{{ $ex->name }}</li>
			@endforeach
		</ul>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<p>Raahaa treenejä halutun päivän laatikkoon.</p>
		<label>HUOM! Voit poistaa treenin päivästä tuplaklikkaamalla treeniä.</label>
	</div>
</div>
<div class="row">
	<div class="col-md-3">
		<label>Maanantai</label>
		<ul class="sortable day-ex-list">
			@if(isset($program_exercises))
				@foreach($program_exercises as $pr_ex)
					@if($pr_ex->pivot['day'] == 1)
						<li 
							data-id="{{$pr_ex->id}}" 
							class="ui-state-highlight draggable ui-draggable ui-draggable-handle"
							style="background-color: {{ $colors[$pr_ex->ex_category_fk] }}"
						>{{$pr_ex->name}}</li>
					@endif
				@endforeach
			@endif
		</ul>
	</div>
	<div class="col-md-3">
		<label>Tiistai</label>
		<ul class="sortable day-ex-list">
			@if(isset($program_exercises))
				@foreach($program_exercises as $pr_ex)
					@if($pr_ex->pivot['day'] == 2)
						<li 
							data-id="{{$pr_ex->id}}" 
							class="ui-state-highlight draggable ui-draggable ui-draggable-handle"
							style="background-color: {{ $colors[$pr_ex->ex_category_fk] }}"
						>{{$pr_ex->name}}</li>
					@endif
				@endforeach
			@endif
		</ul>
	</div>
	<div class="col-md-3">
		<label>Keskiviikko</label>
		<ul class="sortable day-ex-list">
			@if(isset($program_exercises))
				@foreach($program_exercises as $pr_ex)
					@if($pr_ex->pivot['day'] == 3)
						<li 
							data-id="{{$pr_ex->id}}" 
							class="ui-state-highlight draggable ui-draggable ui-draggable-handle"
							style="background-color: {{ $colors[$pr_ex->ex_category_fk] }}"
						>{{$pr_ex->name}}</li>
					@endif
				@endforeach
			@endif
		</ul>
	</div>
	<div class="col-md-3">
		<label>Torstai</label>
		<ul class="sortable day-ex-list">
			@if(isset($program_exercises))
				@foreach($program_exercises as $pr_ex)
					@if($pr_ex->pivot['day'] == 4)
						<li 
							data-id="{{$pr_ex->id}}" 
							class="ui-state-highlight draggable ui-draggable ui-draggable-handle"
							style="background-color: {{ $colors[$pr_ex->ex_category_fk] }}"
						>{{$pr_ex->name}}</li>
					@endif
				@endforeach
			@endif
		</ul>
	</div>
</div>
<div class="row">
	<div class="col-md-3">
		<label>Perjantai</label>
		<ul class="sortable day-ex-list">
			@if(isset($program_exercises))
				@foreach($program_exercises as $pr_ex)
					@if($pr_ex->pivot['day'] == 5)
						<li 
							data-id="{{$pr_ex->id}}" 
							class="ui-state-highlight draggable ui-draggable ui-draggable-handle"
							style="background-color: {{ $colors[$pr_ex->ex_category_fk] }}"
						>{{$pr_ex->name}}</li>
					@endif
				@endforeach
			@endif
		</ul>
	</div>
	<div class="col-md-3">
		<label>Lauantai</label>
		<ul class="sortable day-ex-list">
			@if(isset($program_exercises))
				@foreach($program_exercises as $pr_ex)
					@if($pr_ex->pivot['day'] == 6)
						<li 
							data-id="{{$pr_ex->id}}" 
							class="ui-state-highlight draggable ui-draggable ui-draggable-handle"
							style="background-color: {{ $colors[$pr_ex->ex_category_fk] }}"
						>{{$pr_ex->name}}</li>
					@endif
				@endforeach
			@endif
		</ul>
	</div>
	<div class="col-md-3">
		<label>Sunnuntai</label>
		<ul class="sortable day-ex-list">
			@if(isset($program_exercises))
				@foreach($program_exercises as $pr_ex)
					@if($pr_ex->pivot['day'] == 0)
						<li 
							data-id="{{$pr_ex->id}}" 
							class="ui-state-highlight draggable ui-draggable ui-draggable-handle"
							style="background-color: {{ $colors[$pr_ex->ex_category_fk] }}"
						>{{$pr_ex->name}}</li>
					@endif
				@endforeach
			@endif
		</ul>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<form id="ex-program-form" class="form-horizontal" action="{{ url('new/ex_program') }}" method="POST" enctype='application/json'>
			{{ csrf_field() }}
			<input type="number" name="id" value="<?= (isset($exprogram) && $exprogram !== null) ? $exprogram->id : '-1' ?>" hidden>
			<div class="form-group col-md-12">
				<label>Treeniohjelman nimi</label>
				<input type="text" name="name" value="<?= (isset($exprogram) && $exprogram !== null) ? $exprogram->name : old('name') ?>" required/>
			</div>
			<div class="form-group col-md-12">
				<label>On aktiivinen</label>
				<input type="checkbox" name="isActive" <?= (isset($exprogram) && $exprogram->isActive == 1) ? 'checked' : '' ?>/>
			</div>
			<input type="submit" value="Tallenna treeniohjelma"/>
		</form>
	</div>
</div>
<script src="{{ URL::asset('js/views/exprogram.js') }}"></script>
@endsection