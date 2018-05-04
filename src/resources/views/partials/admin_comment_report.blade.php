<div data-id="{{ $report->id }}"class="report report_comment col-12">
	<div class="report_principal_info">
		<p>Comment by user <a href="{{ route('user_profile', ['username' => $report->users->username])}}">{{$report->users->username}}</a> has been reported</p>
		<a href="{{ route('commentReportView', ['report_id' => $report->id])}}">More info</a>
		<div class="options">
			<button href="{{ route('deleteCommentReport', ['report_id' => $report->id])}}" id="{{$report->id}}" onclick="deleteCommentReport(this)" class="btn">Delete Comment</button>
			<button href="{{ route('dismissReport', ['report_id' => $report->id])}}" id="{{$report->id}}" onclick="dismissReport(this)"class="btn">Dismiss Report</button>
		</div>
	</div>
</div>