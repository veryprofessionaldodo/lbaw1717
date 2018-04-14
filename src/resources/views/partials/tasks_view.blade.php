@foreach($tasks as $task)
	@include('partials.task', ['task' => $task, 'role' => $role])
@endforeach