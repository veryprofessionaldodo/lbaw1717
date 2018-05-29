<div class="row" id="members">
	<div class="row">
		<div class="col-lg-6 col-12">
			<div class="user_search_settings">
				<label>Invite new team member: </label>
				<input type="text" class="form-control" name="new_team_member" placeholder="Username">
				<a href="{{ route('invite_new_member', ['project_id' => $project->id]) }}"class="new_invite btn btn-primary" type="submit">Send</a>
			</div>
		</div>
		<div class="col-lg-6 col-12">
			<form class="user_search_settings" id="search" method="POST" action="{{ route('project_member_settings_search', ['project_id' => $project->id]) }}">
				<label>Search team member: </label>
				<input type="text" class="form-control" name="username" placeholder="Username">
				<button class="btn btn-primary" type="submit">Search</button>
			</form>
		</div>
	</div>

	<div class="col-12" id="show_members_settings">
		@include('partials.settings_members_view', ['project' => $project, 'members' => $members])
	</div>
</div>