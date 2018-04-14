<div class="col-12">
	@foreach($tasks as $task)
		@include('partials.task', ['task' => $task, 'role' => $role])
	@endforeach
</div>