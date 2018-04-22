<!-- Sprint -->
<div class="list-group-item">
	<a data-toggle="collapse" data-target="#sprint-{{$sprint->id}}" aria-expanded="true">
		<i class="fas fa-sort-down"></i>{{$sprint->name}}</a>

	<?php 
		$a = new \DateTime('now'); 
		$b = new \DateTime($sprint->deadline);
		$diff = $a->diff($b);
		$diff = $diff->format('%d');
	?>
	<p>{{ $diff }} days until deadline</p>

	@if($role == 'co')
		<a href="" class="btn edit_sprint"><i class="fas fa-pencil-alt"></i></a>
	@endif
	<span class="badge badge-primary badge-pill">{{ sizeof($sprint->tasks)}}</span>
</div>

<!-- Tasks -->
<div class="list-group panel-collapse collapse in" id="sprint-{{$sprint->id}}">

	@foreach($sprint->tasks as $task)
		@include('partials.task', ['task' => $task, 'role' => $role])
	@endforeach
	
	@if($role == 'co')
		<div class="sprint-task create_task">
			<input type="text" class="form_control" name="new_task">
			<a class="btn">New Task</a>
		</div>
	@endif
</div>
