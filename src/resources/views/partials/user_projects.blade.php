@foreach($projects as $project)
	@include('partials.user_project', ['project' => $project,'user' => $user])
@endforeach

@if($pagination == "get")
    <div class="get" id="pagination_section">
@else
    <div class="post" id="pagination_section">
@endif
    {{$projects->links()}}
</div>
