<div class="report report_comment col-12">
	<div class="report_principal_info">
		<p>Comment by user <a href="{{ route('user_profile', ['username' => $report->users->username])}}">{{$report->users->username}}</a> has been reported</p>
		<a href="#">More info</a>
		<div class="options">
			<button class="btn">Delete Comment</button>
			<button class="btn">Delete User</button>
			<button class="btn">Dismiss Report</button>
		</div>
	</div>
	<div class="report_details">
		<p><span>{{ date( "d/m/Y", strtotime($report->date))}}</span> , by user <a href="{{ route('user_profile', ['username' => $report->comments_reported->user->username])}}">{{$report->comments_reported->user->username}}</a><!-- on project <strong>Goat_Simulator</strong>--></p>
		<!-- Fazer isto! -->
		<a href="#">Link to Comment</a>
		<h4>Comment Content: </h4>
		<p>"{{ $report->comments_reported->content}}"</p>
		<h4>Details: </h4>
		<p>{{ $report->summary }}</p>
	</div>
</div>