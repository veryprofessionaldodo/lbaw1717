<div class="col-12" id="members_project">
	<div class="row">
		
	
		<div class="col-12 first_col">
			<div id="user_search">
				<label>Search project user: </label>
				<input type="text" class="form-control" name="new_team_member" placeholder="Username">
				<button class="btn btn-primary" type="submit">Search</button>
			</div>
		</div>

		@foreach($members as $member)
			@include('partials.member', ['user' => $member])
		@endforeach

	</div>
	
</div>