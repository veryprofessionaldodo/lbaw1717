<a href="{{ route('project', ['id' => $project->id])}}">
	<div class="project">
		<h5>{{ $project->name }}</h5>
		@if($project->iscoordinator == TRUE)
			<p>Coordinator</p>
		@else
			<p>Team Member</p>
		@endif
		<div class="project_info">
			@if($project->num_members > 1)
				<p>{{ $project->num_members }} Members</p>
			@else
				<p>{{ $project->num_members }} Member</p>
			@endif

			@if($project->sprints_num > 1)
				<span>{{ $project->sprints_num }} Sprints</span>
			@else
				<span>{{ $project->sprints_num }} Sprint</span>
			@endif
			
			<button class="btn btn-secondary">
				<i class="fas fa-sign-out-alt"></i>
			</button>
		</div>
	</div>
</a>