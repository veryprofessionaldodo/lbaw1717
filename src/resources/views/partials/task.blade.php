<div class="sprint-task">
	<a data-toggle="collapse" data-target="#task-{{$task->id}}" aria-expanded="false">
		<i class="fas fa-sort-down"></i></a>
	<p>{{$task->name}}</p>
	@if($role == 'tm')
		<button class="btn">Claim task</button>
	@elseif($role == 'co')
		<div class="coordinator_options">
			<button class="btn edit_task"><i class="fas fa-pencil-alt"></i></button>
			<button class="btn">Assign task</button>
		</div>
	@endif
	<input type="checkbox">
</div>

<div class="list-group panel-collapse collapse in" id="task-{{$task->id}}">

	@each('partials.comment', $task->comments, 'comment')
	
	<div class="comment">
		<p class="label">New comment:</p>
		<div class="form_comment">
			<form method="POST" action="{{ route('create_comment_task',['project_id' => $project->id,'task_id' => $task->id]) }}">
				{{ csrf_field()}}
				<input type="text" class="form-control" name="content">
				<button class="btn btn-primary" type="submit">Send</button>
			</form>
		</div>
	</div>
</div>