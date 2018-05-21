<div class="col-12 member">
	<div class="row">
		<div class="info col-sm-8 col-12">
			@if($user->image != NULL)
				<img alt="Profile Image" src="{{$user->image}}">
			@else
				<img alt="Profile Default Image" src="{{ asset('storage/'.'1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg')}}">
			@endif
			<a href="{{ route('user_profile', ['username' => $user->username])}}">{{$user->username}}</a>
		</div>
		@if($user->pivot->iscoordinator == TRUE)
			<p class="col-sm-4 col-12">Coordinator</p>
		@else
			<p class="col-sm-4 col-12">Team Member</p>
		@endif
	</div>
</div>