<!-- Task -->
<div class="sprint-task">
	<a data-toggle="collapse" href="#sprint-1-c-1"><i class="fas fa-sort-down"></i></a>
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
<!-- Comments -->
<div class="list-group collapse" id="sprint-1-c-1">

	@each('partials.comment', $task->comments, 'comment')
	
	<div class="comment">
		<p class="label">New comment:</p>
		<div class="form_comment">
			<input type="text" class="form-control" name="comment">
			<button class="btn btn-primary" type="submit">Send</button>
		</div>
	</div>
</div>