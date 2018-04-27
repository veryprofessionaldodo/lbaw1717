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
			<nav class="navbar navbar-expand-lg navbar-dark" id="buttons_nav">

				<a class="btn btn-secondary" class="project_buttons" href="{{route('forum',['project_id' => $project->id])}}">
					<i class="fas fa-comments"></i> Forum</a>

				<a class="btn btn-secondary" class="project_buttons" href="{{route('project_stats',['project_id' => $project->id])}}">
					<i class="fas fa-chart-line"></i> Statistics</a>
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
					<a class="nav-link active" id="sprint_btn" data-toggle="tab" href="{{ route('project_sprints', ['project_id' => $project->id])}}"><i class="fas fa-bolt"></i>  Sprints</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="task_btn" data-toggle="tab" href="{{ route('project_tasks', ['project_id' => $project->id])}}"><i class="far fa-sticky-note"></i>  Tasks</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="member_btn" data-toggle="tab" href="{{ route('project_members', ['project_id' => $project->id])}}"><i class="fas fa-users"></i> Members</a>
				</li> 
			</ul>
		</div>
	</div>


	<div class="row content_view">
		
		@include('partials.sprints_view', ['sprints' => $sprints, 'role' => $role])

	</div>

</section>

@elseif($role == 'co')

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
		<div class="col-12 new_sprint">
			<button class="btn btn-info"><i class="fas fa-plus"></i> Create New Sprint</button>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="buttons_nav">
				<a class="btn btn-secondary" class="project_buttons" href="{{route('forum',['project_id' => $project->id])}}">
						<i class="fas fa-comments"></i> Forum</a>
				<a class="btn btn-secondary" class="project_buttons" href="{{route('project_stats',['project_id' => $project->id])}}">
						<i class="fas fa-chart-line"></i> Statistics</a>
				<a class="btn btn-secondary" class="project_buttons" href="">
					<i class="fas fa-cog"></i> Settings</a>
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
				<button type="button" class="btn btn-secondary" id="project_buttons">
					<i class="fas fa-cog"></i>
				</button>
			</nav>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<ul class="nav nav-tabs">
				
				<li class="nav-item">
					<a class="nav-link active" id="sprint_btn" data-toggle="tab" href="{{ route('project_sprints', ['project_id' => $project->id])}}"><i class="fas fa-bolt"></i>  Sprints</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="task_btn" data-toggle="tab" href="{{ route('project_tasks', ['project_id' => $project->id])}}"><i class="far fa-sticky-note"></i>  Tasks</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="member_btn" data-toggle="tab" href="{{ route('project_members', ['project_id' => $project->id])}}"><i class="fas fa-users"></i> Members</a>
				</li> 
			</ul>
		</div>
	</div>



	<div class="row content_view">
		
		@include('partials.sprints_view', ['sprints' => $sprints, 'role' => $role])
		
	</div>

</section>

@elseif($role == 'guest')


@endif

@endif

@endsection


