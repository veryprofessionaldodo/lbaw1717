<div class="col-12 member">
	<div class="row">
		<div class="info col-sm-8 col-12">
			<img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png">
			<a href="#">{{$user->username}}</a>
		</div>
		@if($user->pivot->iscoordinator == TRUE)
			<p class="col-sm-4 col-12">Coordinator</p>
		@else
			<p class="col-sm-4 col-12">Team Member</p>
		@endif
	</div>
</div>