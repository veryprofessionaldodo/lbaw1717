<div class="report_details">
    <p><span>{{ date( "d/m/Y", strtotime($report->date))}}</span> , by user <a href="{{ route('user_profile', ['username' => $report->users->username])}}">{{$report->users->username}}</a></p>
    <h4>Details: </h4>
    <p>{{$report->summary}}</p>
</div>