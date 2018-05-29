<div class="col-12" id="members_project">
	<div class="row">
		<div class="col-12">
			<form id="user_search" method="POST" action="{{ route('project_member_search', ['project_id' => $project->id]) }}">
				<label>Search project user: </label>
				<input type="text" class="form-control" name="username" placeholder="Username">
				<button class="btn btn-primary" type="submit">Search</button>
			</form>
		</div>

		<div class="row" id="show_members">
			@include('partials.members_view', ['members' => $members])
		</div>

	</div>
	
</div>