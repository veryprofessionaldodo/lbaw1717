<div class="col-12">
	@foreach($tasks as $task)
		@include('partials.task', ['task' => $task, 'role' => $role])
	@endforeach


	@if($role == 'co')
		<form class="sprint-task create_task" method="POST" action="{{ route('new_task', ['project_id' => $project->id]) }}">
			{{ csrf_field() }}
			<input type="text" class="form_control" name="new_task" placeholder="Task Name">
			<input type="number" class="form_control" name="effort" min="1" max="20" placeholder="Effort">
			<a class="btn" data-id="{{ $project->id }}">New Task</a>
		</form>
	@endif
</div>

