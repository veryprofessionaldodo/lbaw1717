@extends('layouts.app')

@section('title', 'Project')

@section('content')

@if($role == 'tm')

<section class="container-fluid">
	<span data-toggle="tooltip" data-placement="top"
	title="If you have any questions regarding this page, you can clarify them at the FAQ (in the footer)">
		<i class="fas fa-info-circle"></i>
	</span>
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
	<span data-toggle="popover" data-placement="top"
		title="If you have any questions regarding this page, you can clarify them at the FAQ (in the footer)">
		<i class="fas fa-info-circle"></i>
	</span>
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

	
<div class="{{$role}}"id="project_structure">
		@if(Auth::user()->isadmin == false)
		<div class="row">
			<div class="col-12 new_sprint">
				<a href="{{ route('new_sprint_form', ['project_id' => $project->id])}}" class="btn btn-info"><i class="fas fa-plus"></i> Create New Sprint</a>
			</div>
		</div>
		@else
		<div class="row">
				<div class="col-12 new_sprint">
					<a href="{{ route('delete_project', ['project_id' => $project->id])}}" id="delete_project" class="btn btn-info"><i class="fas fa-trash"></i> Delete Project</a>
				</div>
			</div>
		@endif
		<div class="row">
			<div class="col-12">
				<nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="buttons_nav">
					<a class="btn btn-secondary" class="project_buttons" href="{{route('forum',['project_id' => $project->id])}}">
							<i class="fas fa-comments"></i> Forum</a>
					<a class="btn btn-secondary" class="project_buttons" href="{{route('project_stats',['project_id' => $project->id])}}">
							<i class="fas fa-chart-line"></i> Statistics</a>
					<a class="btn btn-secondary" class="project_buttons" href="{{route('project_settings',['project_id' => $project->id])}}">
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

	</div>

</section>

@elseif($role == 'guest')

<section class="container-fluid">

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
		<div class="col-12 edit_project">
			<a href="{{ route('request_join_project', ['project_id' => $project->id])}}" class="btn btn-info"><i class="fas fa-sign-in-alt"></i> Join Project</a>
		</div>
	</div>
	<div class="row">
	<div class="col-2" style:"text-align: center;">
		<nav class="navbar navbar-expand-lg navbar-dark" id="buttons_nav">

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


<div class="col-12" id="members_project">
	<div class="row" id="show_members">
	
		@include('partials.members_view', ['members' => $members, 'project' => $project])

	</div>
</div>

</section>


@endif

@endsection


