@extends('layouts.app')

@section('title', 'New_Thread_Form')

@section('content')

@if(Auth::check())

<div id="container">

    <div id="options">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('project', ['id' => $project->id])}}">Project</a></li>            
            <li class="breadcrumb-item"><a href="{{ route('forum', ['id' => $project->id])}}">Forum</a></li>            
            <li class="breadcrumb-item active">New Thread</li>            
        </ol>
    </div>

    <div id="overlay">
        <div class="jumbotron">
            <h3 class="display-3">New thread</h3>
            <p class="lead"></p>
            <hr class="my-4">
            <div class="form-group">
                <label class="col-form-label" for="title">Insert the title of the new issue: </label>
                <input type="text" class="form-control" name="title" placeholder="Title..." id="title">
            </div>
            <div class="form-group">
                <label class="col-form-label" for="description">Insert the description of the issue: </label>
                <textarea class="form-control" id="description" name="description" placeholder ="Description..." rows="5"></textarea>
            </div>
            <p class="lead">
                <a class="btn btn-primary btn-lg" id="newThread-btn" href="{{ route('new_thread_action', ['id' => $project->id])}}" role="button">Create</a>
            </p>
        </div>   
    </div>
</div>

@else

@endif

@endsection