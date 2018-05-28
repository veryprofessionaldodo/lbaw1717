 
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
			<div class="col-12 edit_project">
				<a href="{{ route('edit_project_form', ['project_id' => $project->id])}}" class="btn btn-info"><i class="fas fa-edit"></i> Edit Project Info</a>
			</div>
		</div>

    	<div class="row">
			<div class="col-12">
				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="nav-link active" id="requests"data-toggle="tab" href="{{route('project_settings_requests', ['project_id' => $project->id])}}"><i class="far fa-sticky-note"></i>  Requests</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="members" data-toggle="tab" href="{{route('project_settings_members', ['project_id' => $project->id])}}"><i class="fas fa-users"></i> Members</a>
					</li> 
				</ul>
			</div>
		</div>

		@include('partials.project_settings_requests', ['project' => $project, 'requests' => $requests])
	    
    </section>
    
    @endif
    
    @endsection
    