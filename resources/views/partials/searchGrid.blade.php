<div class="grid">
    <span id="result_message"></span>
    @foreach($items as $item)
		<div class="grid-item" data-name="{{ $item["name"] }}" data-l="{{$item["lname"]}}" data-f="{{$item["fname"]}}" data-email="{{$item["email"]}}">
			<a href="{{ url('profile/'.$item["id"])}}">
				<div class="profile-img-container" style="background-image: url({{URL::asset('storage/avatars/'.$item["img"]) }});" title="{{ $item["name"] }}" /></div>
			</a>
			<a href="{{ url('profile/'.$item["id"])}}">
				<p>{{ $item["name"] }}</p>
			</a>
			<button class="btn btn-default friendReq_btn" id="{{ $item["id"] }}" title="{{trans('main.add_friend')}}"><span class="glyphicon glyphicon-heart"></span></button>
		</div>
    @endforeach
</div>


