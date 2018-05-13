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
                    <p>User Assigned:</p>
                    @if($last_record->state == "Assigned")
                        @if($last_record->user->image != NULL)
                            <img src="{{ asset('storage/'. $last_record->user->image)}}" title="{{ $last_record->user->username}}">
                        @else
                            <img src="{{ asset('storage/'. '1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg')}}" title="{{ $last_record->user->username}}">
                        @endif
                    @endif
                </div>
                
                <div class="task_options col-9">
                           
                    @if($coordinator)
                        <div class="coordinator_options">
                            @if($task->isUserAssigned(Auth::id()) == null)
                                <a class="btn btn-primary claim" href="{{ route('assign_self', ['project_id' => $project->id, 'task_id' => $task->id])}}">
                                    Claim task</a>
                            @else
                                <a class="btn btn-primary claim" href="{{ route('unassign_self', ['project_id' => $project->id, 'task_id' => $task->id])}}">
                                    Unclaim task</a>
                            @endif
                            <button class="btn btn-primary" id="assign_user">Assign task to user</button>

                            <div id="assign_user_form">
                                <input class="form-control user_name" type="text" name="user_username" placeholder="username">
                                <button class="btn btn-primary send_name"><i class="fas fa-caret-right"></i></button>
                            </div>

                            <button class="btn btn-warning edit_task"><i class="fas fa-pencil-alt"></i></button>
                            <button class="btn btn-danger delete_task"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    @else
                        @if($task->isUserAssigned(Auth::id()) == null)
                            <a class="btn claim" href="{{ route('assign_self', ['project_id' => $project->id, 'task_id' => $task->id])}}">
                                Claim task</a>
                        @else
                            <a class="btn claim" href="{{ route('unassign_self', ['project_id' => $project->id, 'task_id' => $task->id])}}">
                                Unclaim task</a>
                        @endif
                    @endif
                </div>
                
                <div class="col-1" id="checkbox" >
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