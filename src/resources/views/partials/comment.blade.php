<div data-id="{{ $comment->id }}" class="comment">
	<img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png">
	<a href="{{ route('user_profile', ['username' => $comment->user->username])}}">{{$comment->user->username}}</a> <!-- TODO ANCHOR and adapte css-->
	<p>{{$comment->content}}</p>
	<?php  
		$date = new \DateTime($comment->deadline);
	?>
	<span>{{$date->format('d/m/Y')}}</span>
	<div class="user_options">
		@if(Auth::user()->id != $comment->user->id && $role == 'tm')
			<a href="{{ route('comment_report_form', ['comment_id' => $comment->id])}}" class="btn"><i class="fas fa-flag"></i></a>
		@elseif(Auth::user()->id != $comment->user->id && $role == 'co')
			<a href="{{ route('comment_report_form', ['comment_id' => $comment->id])}}" class="btn"><i class="fas fa-flag"></i></a>
			@if($comment->task_id == NULL)
				<button href="{{ route('deleteCommentThread', ['id' => $project->id, 'thread_id' => $thread->id, 'comment_id' => $comment->id])}}" onclick="deleteComment(this)" id="{{$comment->id}}"class"deleteComment" ><i class="fas fa-trash"></i></button>
			@else
				<button href="{{ route('deleteCommentTask', ['id' => $project->id, 'task_id' => $task->id, 'comment_id' => $comment->id])}}" onclick="deleteComment(this)" id="{{$comment->id}}"class"deleteComment" ><i class="fas fa-trash"></i></button>
			@endif
		@elseif(Auth::user()->id == $comment->user->id)
			@if($comment->task_id == NULL)
				<button href="{{ route('deleteCommentThread', ['id' => $project->id, 'thread_id' => $thread->id, 'comment_id' => $comment->id])}}" onclick="deleteComment(this)" id="{{$comment->id}}"class"deleteComment" ><i class="fas fa-trash"></i></button>
			@else
				<button href="{{ route('deleteCommentTask', ['id' => $project->id, 'task_id' => $task->id, 'comment_id' => $comment->id])}}" onclick="deleteComment(this)" id="{{$comment->id}}"class"deleteComment" ><i class="fas fa-trash"></i></button>
			@endif
		@endif
	</div>
</div>
	