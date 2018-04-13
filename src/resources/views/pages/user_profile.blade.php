@extends('layouts.app')

@section('title', 'User_Profile')

@section('content')

@if(Auth::check())

<div class="container-fluid">
		<div class="row">
			<aside class="col-lg-2 col-md-3 col-12">
				<div class="row">
					<div id="info" class="col-md-12 col-6">
						@if(Auth::user()->image != NULL)
							<img src="{{url(Auth::user()->image)}}">
						@else						
							<img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png">
						@endif
						<div id="info_text">
							<h3>{{ Auth::user()->name }}</h3>
							<p>{{ Auth::user()->username }}</p>
							<p>{{ Auth::user()->email }}</p>
						</div>
					</div>

					<div id="statistics" class="col-md-12 col-6">
						<h5>Statistics</h5>
						<p>{{ $taskCompletedWeek->count }} tasks completed this week</p>
						<p>{{ $taskCompletedMonth->count }} tasks completed this month</p>
						<p>{{ $sprintsContributedTo->count }} sprints contributed to</p>
					</div>

					<a href="#" class="col-md-12 col-12">Edit Profile</a>
				</div>
			</aside>

			<section class="col-lg-10 col-md-9 col-12">
				<div id="options">
					<div class="row">

						<!-- Add functionality to this button -->
						<div class="offset-lg-1 col-lg-2 col-md-12">
							<button id="new_project" class="btn btn-primary">Create New Project</button>
						</div>

						<!-- Add functionality to this form -->
						<form class="col-lg-6 col-md-7 col-sm-7 col-12">
							<input type="text" name="search" placeholder="Search" class="form-control">
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

				<!-- Add functionality to this form -->
				<form id="new_project_name" class="hide">
					<input type="text" class="form-control" name="name_project" placeholder="name of project">
					<button class="btn btn-primary my-2 my-sm-0" type="submit">Submit</button>
				</form>

				@each('partials.user_project', $projects, 'project')
				
				<div id="pagination_section" class="col-12">
				  <ul class="pagination">
				    <li class="page-item disabled">
				      <a class="page-link" href="#">&laquo;</a>
				    </li>
				    <li class="page-item active">
				      <a class="page-link" href="#">1</a>
				    </li>
				    <!--<li class="page-item">
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
				    </li>-->
				  </ul>
				</div>
				
			</section>
		</div>
	</div>

@else



@endif

@endsection