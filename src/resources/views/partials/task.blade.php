<!-- Task -->
	<div class="sprint-task">
		<a data-toggle="collapse" href="#sprint-1-c-1"><i class="fas fa-sort-down"></i></a>
		<p>{{$task->name}}</p>
		<button class="btn">Claim task</button>
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