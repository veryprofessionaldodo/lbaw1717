<div class="report col-12">
	<div class="report_principal_info">
		<p>User <a href="{{ route('user_profile', ['username' => $report->users_reported->username])}}">{{$report->users_reported->username}}</a> has been reported</p>
		<a href="#">More info</a>
		<div class="options">
			<button class="btn">Delete User</button>
			<button href="{{ route('dismissReport', ['report_id' => $report->id])}}" id="{{$report->id}}" onclick="dismissReport(this)"class="btn">Dismiss Report</button>
		</div>
	</div>
	<div class="report_details">
		<p><span>{{ date( "d/m/Y", strtotime($report->date))}}</span> , by user <a href="{{ route('user_profile', ['username' => $report->users->username])}}">{{$report->users->username}}</a></p>
		<h4>Details: </h4>
		<p>{{$report->summary}}</p>
	</div>
</div>