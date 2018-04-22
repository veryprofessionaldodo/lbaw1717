@foreach($projects as $project)
	@include('partials.user_project', ['project' => $project,'user' => $user])
@endforeach

<div id="pagination_section">
    {{$projects->links()}}
</div>
