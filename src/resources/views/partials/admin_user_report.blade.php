<div data-id="{{ $report->id }}" class="report report_user col-12">
	<div class="report_principal_info">
		<p>User <a href="{{ route('user_profile', ['username' => $report->users_reported->username])}}">{{$report->users_reported->username}}</a> has been reported</p>
		<a class="info"href="{{ route('userReportView', ['report_id' => $report->id])}}">More info</a>
		<div class="options">
			<button href="{{ route('disableUser', ['report_id' => $report->id])}}" onclick="disableUser(this)"class="btn">Delete User</button>
			<button href="{{ route('dismissReport', ['report_id' => $report->id])}}" onclick="dismissReport(this)"class="btn">Dismiss Report</button>
		</div>
	</div>
</div>