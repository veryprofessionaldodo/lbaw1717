@extends('layouts.app')

@section('title', 'Project')

@section('content')

<div id="all_projects" class="container-fluid">
	<div class="row">

		{{--  <!-- TODO: Implementar isto -->  --}}
		<div id="filter" class="col-12">
			<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sort By:</button>
		</div>

		@foreach($projects as $project)
				@include('partials.project_search', ['project' => $project])
		@endforeach
		
		{{--  <div id="pagination_section">
			{{$projects->links()}}
		</div>  --}}

    </div>
</div>

@endsection