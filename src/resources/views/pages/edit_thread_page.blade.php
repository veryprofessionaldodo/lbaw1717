@extends('layouts.app')

@section('title', 'Edit Thread Form')

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
            <h3 class="display-3">Edit thread</h3>
            <p class="lead"></p>
            <hr class="my-4">

            <form method="POST" action="{{ route('edit_thread_action', ['id' => $project->id, 'thread_id' => $thread->id])}}">
                {{csrf_field()}}
                <div class="form-group">
                    <label class="col-form-label" for="title">Insert the new title: </label>
                    <input type="text" class="form-control" name="title" placeholder="Title..." id="title" value="{{$thread->name}}" required></input>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="description">Insert the description of the issue: </label>
                    <textarea class="form-control" id="description" name="description" placeholder ="Description..." rows="5">{{$thread->description}}</textarea>
                </div>
                <p class="lead">
                    <button class="btn btn-primary btn-lg" id="newThread-btn" href="" type="submit">Save</button>
                </p>
            </form>
        </div>   
    </div>
</div>

@else

@endif

@endsection