@extends('layouts.app_task')

@section('title', 'Task Page')

@section('content')

@if(Auth::check())

<?php 
	$last_record = $task->task_state_records->last();
?>

<section class="container-fluid">

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

    <div class="row">
        <div class="col-12">
            <h1>{{$task->name}}</h1>
        </div>

        <div class="col-12">

            <div class="row">
                
                <div class="assigned_users col-2">
                    
                </div>
                
                <div class="task_options col-8">
                           
                    @if($coordinator)
                        <div class="coordinator_options">
                            @if($task->isUserAssigned(Auth::id()) == null)
                                <a class="btn claim" href="{{ route('assign_self', ['project_id' => $project->id, 'task_id' => $task->id])}}">
                                    Claim task</a>
                            @else
                                <a class="btn claim" href="{{ route('unassign_self', ['project_id' => $project->id, 'task_id' => $task->id])}}">
                                    Unclaim task</a>
                            @endif
                            <button class="btn">Assign task to user</button>
                            <input class="form-control user_name hidden" type="text" name="user_username">
                            <button class="btn send_name hidden"><i class="fas fa-caret-right"></i></button>
                            <button class="btn edit_task"><i class="fas fa-pencil-alt"></i></button>
                            <button class="btn delete_task"><i class="fas fa-trash-alt"></i></button>
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
                
                @if($last_record->state == "Completed")
                    <input data-url="{{ route('update_task', ['project_id' => $project->id, 'task_id' => $task->id])}}" type="checkbox" class="col-2" checked>    
                @else
                    <input data-url="{{ route('update_task', ['project_id' => $project->id, 'task_id' => $task->id])}}" type="checkbox" class="col-2">
                @endif

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