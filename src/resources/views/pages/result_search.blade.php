@extends('layouts.app')

@section('title', 'Project')

@section('content')

<div id="all_projects" class="container-fluid">
	<div class="row">

		@foreach($projects as $project)
				@include('partials.project_search', ['project' => $project])
		@endforeach
		
		<div id="pagination_section">
			{{$projects->links()}}
		</div>  
		

    </div>
</div>

@endsection