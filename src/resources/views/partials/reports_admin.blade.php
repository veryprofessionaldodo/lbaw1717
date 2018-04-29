@foreach($reports as $report)
  @if($type == 'comment')
    @include('partials.admin_comment_report', ['report' => $report])
  @elseif($type == 'user')
    @include('partials.admin_user_report', ['report' => $report])
  @endif
@endforeach

<div id="pagination_section">
  {{$reports->links()}}
</div>