<div class="report_details">
    <p><span>{{ date( "d/m/Y", strtotime($report->date))}}</span> , by user <a href="{{ route('user_profile', ['username' => $report->comments_reported->user->username])}}">{{$report->comments_reported->user->username}}</a><!-- on project <strong>Goat_Simulator</strong>--></p>
    <h4>Comment Content: </h4>
    <p>"{{ $report->comments_reported->content}}"</p>
    <h4>Details: </h4>
    <p>{{ $report->summary }}</p>
</div>