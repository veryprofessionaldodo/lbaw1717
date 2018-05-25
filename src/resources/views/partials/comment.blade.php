<div data-id="{{ $comment->id }}" class="comment">

	@if($comment->user->image != NULL)
		<img alt="Profile Image" src="{{ asset('storage/'.$comment->user->image)}}">
	@else						
		<img alt="Profile Default Image" src="{{ asset('storage/'.'1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg')}}">
	@endif
	
	<a href="{{ route('user_profile', ['username' => $comment->user->username])}}">{{$comment->user->username}}</a> <!-- TODO ANCHOR and adapte css-->
	<p id="content">{{$comment->content}}</p>
	
	@if($comment->task_id == NULL)
	<form id="edit" class="col-12 hidden" hidden="true" method="POST" action="{{route('editCommentThread', ['id' => $project->id, 'thread_id' => $thread->id, 'comment_id' => $comment->id])}}">
            {{ csrf_field()}}
        <textarea name="description" id="mytextarea" cols="30" rows="10">
        </textarea>

        <button type="submit" class="btn">Submit Changes</button>
      </form>
  	@endif
	
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
				<button href="{{ route('deleteCommentThread', ['id' => $project->id, 'thread_id' => $thread->id, 'comment_id' => $comment->id])}}" onclick="deleteCommentThread(this)" id="{{$comment->id}}" class="deleteComment" ><i class="fas fa-trash"></i></button>
			@else
				<button href="{{ route('deleteCommentTask', ['id' => $project->id, 'task_id' => $task->id, 'comment_id' => $comment->id])}}" onclick="deleteCommentTask(this)" id="{{$comment->id}}" class="deleteComment" ><i class="fas fa-trash"></i></button>
			@endif

		@elseif(Auth::user()->id == $comment->user->id)

			@if($comment->task_id == NULL)
				<button href="{{ route('deleteCommentThread', ['id' => $project->id, 'thread_id' => $thread->id, 'comment_id' => $comment->id])}}" onclick="deleteCommentThread(this)" id="{{$comment->id}}" class="deleteComment" ><i class="fas fa-trash"></i></button>
				<button class="btn btn-warning edit_comment"><i class="fas fa-pencil-alt"></i></button>
			@else
				<button href="{{ route('deleteCommentTask', ['id' => $project->id, 'task_id' => $task->id, 'comment_id' => $comment->id])}}" onclick="deleteCommentTask(this)" id="{{$comment->id}}" class="deleteComment" ><i class="fas fa-trash"></i></button>
			@endif

		@endif
	</div>
</div>
	