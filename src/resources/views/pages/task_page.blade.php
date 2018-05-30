@extends('layouts.app_task')

@section('title', 'Task Page')

@section('content')

@if(Auth::check())

<?php 
	$last_record = $task->task_state_records->last();
?>

<section class="container-fluid" id="task_page">

    @if($last_record->state == "Completed")
        <div class="alert alert-dismissible alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            This task is <strong>completed</strong>.
        </div>
    @endif

    <div id="options">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('project', ['id' => $project->id])}}">Project</a></li>            
            <li class="breadcrumb-item active">{{ $task->name }}</li>            
        </ol>     
    </div>

    <div class="row" id="task">
        <div class="col-12">
            <h1>{{$task->name}}</h1>
        </div>

        <div class="col-12">

            <div class="row">
                
                <div class="assigned_users col-2">
                    @if($last_record->state == "Assigned")
                        <p>User Assigned:</p>
                        @if($last_record->user->image != NULL)
                            <img alt="Profile Image"src="{{ asset('storage/'. $last_record->user->image)}}" title="{{ $last_record->user->username}}">
                        @else
                            <img alt="Profile Default Image"src="{{ asset('storage/'. '1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg')}}" title="{{ $last_record->user->username}}">
                        @endif
                    @endif
                </div>
                
                <div class="task_options col-8">
                    
                    @if($last_record->state !== "Completed")
                        @if($coordinator)

                            @include('partials.task_coordinator_options', ['project' => $project, 'task' => $task])

                        @else

                            @if($task->isUserAssigned(Auth::id()) == null)
                                <a class="btn claim btn-primary" href="{{ route('assign_self', ['project_id' => $project->id, 'task_id' => $task->id])}}">
                                    Claim task</a>
                            @else
                                <a class="btn claim btn-primary" href="{{ route('unassign_self', ['project_id' => $project->id, 'task_id' => $task->id])}}">
                                    Unclaim task</a>
                            @endif

                        @endif
                    @endif

                </div>
                
                <div class="col-2" id="checkbox">
                    <p>Completed</p>
                    @if($last_record->state == "Completed")
                        <input data-url="{{ route('update_task', ['project_id' => $project->id, 'task_id' => $task->id])}}" type="checkbox" checked>    
                    @else
                        <input data-url="{{ route('update_task', ['project_id' => $project->id, 'task_id' => $task->id])}}" type="checkbox">
                    @endif
                </div>

            </div>

        </div>
    </div>
    
    <div class="row" id="task_info">

        <div id="task_description" class="col-12">
            {!! $task->description !!}
        </div>

        <form class="col-12 hidden" method="POST" action="{{route('edit_task', ['project_id' => $project->id, 'task_id' => $task->id])}}">
            {{ csrf_field()}}
            <textarea name="description" id="mytextarea" cols="30" rows="10">
            </textarea>

            <button type="submit" class="btn">Submit Changes</button>
        </form>
    </div>

</section>

@endif
    
@endsection