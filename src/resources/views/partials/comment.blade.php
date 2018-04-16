<div class="comment">
	<img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png">
	<h6>{{$comment->user->username}}</h6>
	<p>{{$comment->content}}</p>
	<span>{{$comment->date}}</span>
	<div class="user_options">
		<p>Report</p>
		<button class="btn"><i class="fas fa-flag"></i></button>
	</div>
</div>
	