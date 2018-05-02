@extends('layouts.app')

@section('title', 'Project Settings')

@section('content')

@if(Auth::check())

    <section class="container-fluid">

    	<ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('project', ['id' => $project->id])}}">Project</a></li>            
            <li class="breadcrumb-item active">Settings</li>            
        </ol>
	<div class="row">
			<div class="col-12">
				<h1>{{$project->name}}</h1>
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
				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="{{route('project_settings_requests', ['project_id' => $project->id])}}"><i class="far fa-sticky-note"></i>  Requests</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="{{route('project_settings_members', ['project_id' => $project->id])}}"><i class="fas fa-users"></i> Members</a>
					</li> 
				</ul>
			</div>
		</div>

		<div class="row" id="members">
			<div class="row">
				<div class="col-lg-6 col-12">
					<div class="user_search">
						<label>Invite new team member: </label>
						<input type="text" class="form-control" name="new_team_member" placeholder="Username">
						<button class="btn btn-primary" type="submit">Send</button>
					</div>
				</div>
				<div class="col-lg-6 col-12">
					<div class="user_search">
						<label>Search team member: </label>
						<input type="text" class="form-control" name="new_team_member" placeholder="Username">
						<button class="btn btn-primary" type="submit">Search</button>
					</div>
				</div>
			</div>

			<div class="col-12">
		    	@foreach($members as $member)
					@include('partials.settings_member', ['project_id' => $project->id, 'member' => $member])
				@endforeach
		    </div>

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
	    
    </section>

    @endif
    
    @endsection