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
			<input type="text" class="form-control" name="comment">
			<button class="btn btn-primary" type="submit">Send</button>
		</div>
	</div>
</div>