<!-- Sprint -->
<div class="list-group-item" data-id="{{$sprint->id}}">
	<a data-toggle="collapse" data-target="#sprint-{{$sprint->id}}" aria-expanded="true">
		<i class="fas fa-sort-down"></i>{{$sprint->name}}</a>

	<?php 
		$a = new \DateTime('now'); 
		$b = new \DateTime($sprint->deadline);
		$diff = $a->diff($b);
		$diff = $diff->format('%a');
	?>
	<p>{{ $diff }} days until deadline</p>

	@if($role == 'co')
		<a href="{{ route('edit_sprint_form', ['project_id' => $project->id , 'sprint_id' => $sprint->id])}}" class="btn edit_sprint"><i class="fas fa-pencil-alt"></i></a>
		<a href="{{ route('delete_sprint', ['project_id' => $project->id , 'sprint_id' => $sprint->id])}}" class="btn delete_sprint"><i class="fas fa-trash"></i></a>
	@endif
	<span class="badge badge-primary badge-pill">{{ sizeof($sprint->tasks)}}</span>
</div>

<!-- Tasks -->
<div class="list-group panel-collapse collapse in" id="sprint-{{$sprint->id}}">

	@foreach($sprint->tasks as $task)
		@include('partials.task', ['task' => $task, 'role' => $role])
	@endforeach
	
	@if($role == 'co')
		<form class="sprint-task create_task" method="POST" action="{{ route('new_task', ['project_id' => $project->id]) }}">
			{{ csrf_field() }}
			<input type="text" class="form_control" name="new_task" placeholder="Task Name" required>
			<input type="number" class="form_control" name="effort" min="1" max="20" placeholder="Effort" required>
			<a class="btn" data-id="{{ $sprint->id}}-{{ $project->id }}">New Task</a>
		</form>
	@endif
</div>
