@extends('layouts.app')

@section('title', 'Edit Thread Form')

@section('content')

@if(Auth::check())

<div id="container">

    <div id="options">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('project', ['project_id' => $project->id])}}">Project</a></li>
            <li class="breadcrumb-item"><a href="{{route('project_settings',['project_id' => $project->id])}}">Settings</a></li>                     
            <li class="breadcrumb-item active">EDIT PROJECT INFO</li>            
        </ol>
    </div>

    <div id="overlay">
        <div class="jumbotron">
            <h3 class="display-3">Edit Project Info</h3>
            <p class="lead"></p>
            <hr class="my-4">

            <form method="POST" action="{{ route('edit_project', ['project_id' => $project->id])}}">
                {{csrf_field()}}
                <div class="form-group">
                    <label class="col-form-label" for="name">Insert the new name: </label>
                    <input type="text" class="form-control" name="name" placeholder="Name..." id="title" value="{{$project->name}}"></input>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="description">Insert the description: </label>
                    <textarea class="form-control" id="description" name="description" placeholder ="Description..." rows="5">{{$project->description}}</textarea>
                </div>
                <div class="form_area custom-checkbox">
                    <label>Public: </label>
                    @if($project->ispublic == true)
                        <input type="checkbox" name="public" id="public" checked>
                    @else
                        <input type="checkbox" name="public" id="public">
                    @endif
                </div>
                <label>Categories  (can be more than one):</label>
                <select name="categories" class="form-control" multiple>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{$category->name}}</option>
                    @endforeach
                </select>
                <p class="lead">
                    <button class="btn btn-primary btn-lg" id="editProject-btn" href="" type="submit">Save</button>
                </p>
            </form>
        </div>   
    </div>
</div>

@else

@endif

@endsection