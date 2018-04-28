@extends('layouts.app')

@section('title', 'User_Profile')

@section('content')

@if(Auth::check())

<div class="container-fluid">
	<div class="row">
		<aside class="col-lg-2 col-md-3 col-12">
			<div class="row">
				<div id="info" class="col-md-12 col-6">
					@if($user->image != NULL)
						<img src="{{ asset('storage/app/'.$user->image)}}">
					@else						
						<img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png">
					@endif
					<div id="info_text">
						<h3>{{ $user->name }}</h3>
						<p>{{ $user->username }}</p>
						<p>{{ $user->email }}</p>
					</div>
				</div>

				<div id="statistics" class="col-md-12 col-6">
					<h5>Statistics</h5>
					<p>{{ $taskCompletedWeek->count }} tasks completed this week</p>
					<p>{{ $taskCompletedMonth->count }} tasks completed this month</p>
					<p>{{ $sprintsContributedTo->count }} sprints contributed to</p>
				</div>

				@if(Auth::user()->username == $user->username)
					<a href="{{ route('edit_profile', ['username' => Auth::user()->username])}}" id="edit_profile" class="col-md-12 col-12">Edit Profile</a>
				@else
					<a href="{{ route('user_report_form', ['username' => $user->username])}}" id="reprt_btn" class="col-md-12 col-12">Report</a>
				@endif
			</div>
		</aside>

		<section class="col-lg-10 col-md-9 col-12">
			<div id="options">
				<div class="row">

					<div class="offset-lg-1 col-lg-2 col-md-12">
						<a id="new_project" class="btn btn-primary" href="{{ route('new_project_form',['username' => Auth::user()->username])}}">Create New Project</a>
					</div>

					<!-- Add functionality to this form -->
					<form class="col-lg-6 col-md-7 col-sm-7 col-12">
						<input type="text" name="search" placeholder="Search Your Projects" class="form-control">
						<button class="btn btn-primary" type="submit">
							<i class="fas fa-search"></i>
						</button>
					</form>

					<!-- Add functionality to this form -->
					<div class="col-lg-2 col-md-5 col-sm-5 col-12" id="role_button">
						<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
							<button type="button" class="btn btn-primary">Role</button>
							<div class="btn-group" role="group">
								<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
								 aria-expanded="false"></button>
								<div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 38px, 0px); top: 0px; left: 0px; will-change: transform;">
									<a class="dropdown-item" href="#">Coordinator</a>
									<a class="dropdown-item" href="#">Team Member</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="projects">
				@if(Auth::user()->username == $user->username)
					@include('partials.user_projects',['projects' => $projects, 'user' => $user])
				@else
					@include('partials.user_projects',['projects' => $public_projects, 'user' => $user])
				@endif
			</div>
						
		</section>
	</div>
</div>

@else



@endif

@endsection