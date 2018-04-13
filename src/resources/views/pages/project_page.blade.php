@extends('layouts.app')

@section('title', 'Project')

@section('content')

@if(Auth::check())

@if($role == 'tm')

<section class="container-fluid">
		<!-- rows iniciais das project pages -->

	<div class="row">
		<div class="col-12">
			<h1>{{ $project->name }}</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<h4>
				<i class="fas fa-angle-right"></i>&nbsp;&nbsp;{{$project->description}}</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="buttons_nav">
				<button type="button" class="btn btn-secondary" id="project_buttons">
					<i class="fas fa-comments"></i> Forum</button>
				<button type="button" class="btn btn-secondary" id="project_buttons">
					<i class="fas fa-chart-line"></i> Statistics</button>
			</nav>
		</div>

		<div id="row_mobile">
			<nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="mobile_nav">
				<button type="button" class="btn btn-secondary" id="project_buttons">
					<i class="fas fa-comments"></i>
				</button>
				<button type="button" class="btn btn-secondary" id="project_buttons">
					<i class="fas fa-chart-line"></i>
				</button>
			</nav>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<a class="nav-link active" data-toggle="tab" href="#Sprints"><i class="fas fa-bolt"></i>  Sprints</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#Tasks"><i class="far fa-sticky-note"></i>  Tasks</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#Members"><i class="fas fa-users"></i> Members</a>
				</li> 
			</ul>
		</div>
	</div>



	<div class="row">
		<div class="col-12" id="sprints">
			<div class="list-group list-group-root well">

				@each('partials.sprint', $sprints, 'sprint')

			</div>
		</div>
	</div>

</section>

@elseif($role == 'co')


@elseif($role == 'guest')


@endif

@endif

@endsection


