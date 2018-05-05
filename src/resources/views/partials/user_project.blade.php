
<a href="{{ route('project', ['id' => $project->id])}}">
	<div class="project">
		<h5>{{ $project->name }}</h5>
		@if($project->pivot->iscoordinator == true)
			<p>Coordinator</p>
		@else
			<p>Team Member</p>
		@endif
		<div class="project_info">
			@if($project->num_members > 1)
				<p>{{ $project->user_count }} Members</p>
			@else
				<p>{{ $project->user_count }} Member</p>
			@endif

			@if($project->sprints_num > 1 && Auth::user()->username == $user->username)
				<span>{{ $project->sprints_count }} Sprints</span>
			@elseif(Auth::user()->username == $user->username)
				<span>{{ $project->sprints_count }} Sprint</span>
				<button class="btn btn-secondary">
					<i class="fas fa-sign-out-alt"></i>
				</button>
			@endif
			
			
		</div>
	</div>
</a>
