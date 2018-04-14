@extends('layouts.app')

@section('title', 'Project')

@section('content')

<div id="all_projects" class="container-fluid">
	<div class="row">

		<!-- TODO: Implementar isto -->
		<div id="filter" class="col-12">
			<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sort By:</button>
		</div>

		@foreach($projects as $project)
			@include('partials.project_search', ['project' => $project])
		@endforeach
		
		<div class="col-12 pagination_div">
		    <div id="pagination_section" class="col-12">
			  <ul class="pagination">
			    <li class="page-item disabled">
			      <a class="page-link" href="#">&laquo;</a>
			    </li>
			    <li class="page-item active">
			      <a class="page-link" href="#">1</a>
			    </li>
			    <li class="page-item">
			      <a class="page-link" href="#">2</a>
			    </li>
			    <li class="page-item">
			      <a class="page-link" href="#">3</a>
			    </li>
			    <li class="page-item">
			      <a class="page-link" href="#">4</a>
			    </li>
			    <li class="page-item">
			      <a class="page-link" href="#">5</a>
			    </li>
			    <li class="page-item">
			      <a class="page-link" href="#">&raquo;</a>
			    </li>
			  </ul>
			</div>
	    </div>
    </div>
</div>

@endsection