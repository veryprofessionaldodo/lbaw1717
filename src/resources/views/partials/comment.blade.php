<div data-id="{{ $comment->id }}" class="comment">

	@if($comment->user->image != NULL)
		<img alt="Profile Image" src="{{ asset('storage/'.$comment->user->image)}}">
	@else						
		<img alt="Profile Default Image" src="{{ asset('storage/'.'1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg')}}">
	@endif
	
	<a href="{{ route('user_profile', ['username' => $comment->user->username])}}">{{$comment->user->username}}</a>
	<p id="{{$comment->id}}" class="content">{{$comment->content}}</p>
	
	@if($comment->task_id == NULL)
		<div class="form_comment row">
		<form id="edit" method="POST" style="display: none;" action="{{ route('editCommentThread', ['id' => $project->id, 'thread_id' => $thread->id, 'comment_id' => $comment->id])}}">
		{{ csrf_field()}}
		
			<input type="text" class="form-control col-10" name="content" id="content">    
			<button type="submit" class="btn btn-primary col-2">Send</button>
			
		</form> 
		</div>
	@else
	<div class="form_comment row" style="display: none;">
		<form id="edit" method="POST" action="{{ route('editTaskComment',['project_id' => $project->id,'task_id' => $task->id, 'comment_id' => $comment->id]) }}">
			{{ csrf_field()}}
			<input type="text" class="form-control col-10" name="content">
			<button class="btn btn-primary col-2" type="submit">Send</button>
		</form>
	</div>
  	@endif
	
	<?php  
		$date = new \DateTime($comment->deadline);
	?>

	<span>{{$date->format('d/m/Y')}}</span>

	<div class="user_options">
		@if(Auth::user()->id != $comment->user->id && $role == 'tm')

			<a href="{{ route('comment_report_form', ['comment_id' => $comment->id])}}" class="btn"
					role="button" data-toggle="tooltip" data-placement="bottom" title="Report Comment">
				<i class="fas fa-flag"></i>
			</a>
		
		@elseif(Auth::user()->id != $comment->user->id && $role == 'co')

			<a href="{{ route('comment_report_form', ['comment_id' => $comment->id])}}" class="btn"
					role="button" data-toggle="tooltip" data-placement="bottom" title="Report Comment">
				<i class="fas fa-flag"></i>
			</a>
			
			@if($comment->task_id == NULL)
				<button href="{{ route('deleteCommentThread', ['id' => $project->id, 'thread_id' => $thread->id, 'comment_id' => $comment->id])}}" 
					onclick="deleteCommentThread(this)" id="{{$comment->id}}" class="deleteComment" 
					data-toggle="tooltip" data-placement="bottom" title="Delete Comment">
					<i class="fas fa-trash"></i>
				</button>
			@else
				<button href="{{ route('deleteCommentTask', ['id' => $project->id, 'task_id' => $task->id, 'comment_id' => $comment->id])}}" 
					onclick="deleteCommentTask(this)" id="{{$comment->id}}" class="deleteComment" 
					data-toggle="tooltip" data-placement="bottom" title="Delete Comment">
					<i class="fas fa-trash"></i>
				</button>
			@endif

		@elseif(Auth::user()->id == $comment->user->id)

			@if($comment->task_id == NULL)
				<button href="{{ route('deleteCommentThread', ['id' => $project->id, 'thread_id' => $thread->id, 'comment_id' => $comment->id])}}" 
					onclick="deleteCommentThread(this)" id="{{$comment->id}}" class="deleteComment" 
					data-toggle="tooltip" data-placement="bottom" title="Delete Comment"><i class="fas fa-trash"></i>
				</button>
				<button class="btn btn-warning edit_comment" onclick="editCommentThread(this)" id="{{$comment->id}}"
						data-toggle="tooltip" data-placement="bottom" title="Edit Comment"><i class="fas fa-pencil-alt" ></i>
				</button>
			@else
				<button href="{{ route('deleteCommentTask', ['id' => $project->id, 'task_id' => $task->id, 'comment_id' => $comment->id])}}" 
					onclick="deleteCommentTask(this)" id="{{$comment->id}}" class="deleteComment" 
					data-toggle="tooltip" data-placement="bottom" title="Delete Comment"><i class="fas fa-trash"></i>
				</button>
				<button class="btn btn-warning edit_comment" onclick="prepareForEdition(this)" id="{{$comment->id}}"
						data-toggle="tooltip" data-placement="bottom" title="Edit Comment"><i class="fas fa-pencil-alt" ></i>
				</button>
			@endif

		@endif
	</div>
</div>
	