<div class="comment">
	<img src="res/profile/profile-pic.png">
	<h6>{{$comment->user->username}}</h6>
	<p>{{$comment->content}}</p>
	<span>{{$comment->date}}</span>
	<div class="user_options">
		<p>Report</p>
		<button class="btn"><i class="fas fa-flag"></i></button>
	</div>
</div>
	