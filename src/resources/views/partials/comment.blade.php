<div data-id="{{ $comment->id }}" class="comment">
	<img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png">
	<h6>{{$comment->user->username}}</h6>
	<p>{{$comment->content}}</p>
	<?php  
		$date = new \DateTime($comment->deadline);
	?>
	<span>{{$date->format('d/m/Y')}}</span>
	<div class="user_options">
		<a href="{{ route('comment_report_form', ['comment_id' => $comment->id])}}" class="btn"><i class="fas fa-flag"></i></a>
		@if($comment->task_id == NULL)
			<button href="{{ route('deleteCommentThread', ['id' => $project->id, 'thread_id' => $thread->id, 'comment_id' => $comment->id])}}" onclick="deleteComment(this)" id="{{$comment->id}}"class"deleteComment" ><i class="fas fa-trash"></i></button>
		@else
			<button href="{{ route('deleteCommentTask', ['id' => $project->id, 'task_id' => $task->id, 'comment_id' => $comment->id])}}" onclick="deleteComment(this)" id="{{$comment->id}}"class"deleteComment" ><i class="fas fa-trash"></i></button>
		@endif
	</div>
</div>
	