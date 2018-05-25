@if($project->ispublic) <!-- TODO not tested this yet-->

<div class="project col-12">
	<div class="row">
		<div class="col-sm-6 col-12 title_desc">
			<a href="" ref="#" class="project_title">{{$project->name}}</a>
			<p class="project_description ">{{$project->description}}</p>
		</div>

		<a href="{{ route('request_join_project', ['project_id' => $project->id])}}" class="join btn btn-primary col-sm-2 col-12">Join</a>
	</div>  
</div>

@else
@endif