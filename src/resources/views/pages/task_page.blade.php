@extends('layouts.app')

@section('title', 'Project Settings')

@section('content')

@if(Auth::check())

<section class="container-fluid">

    <div id="options">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('project', ['id' => $project->id])}}">Project</a></li>            
            <li class="breadcrumb-item active">{{ $task->name }}</li>            
        </ol>     
    </div>

    <div class="row">
        <div class="col-12">
            <h1>Bitconnect</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4>
                <i class="fas fa-angle-right"></i>&nbsp;&nbsp;Build trust and reputation in bitcoin and cryptocurrency ecosystem with Open-source platform.</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="buttons_nav">
                <button type="button" class="btn btn-secondary" id="project_buttons">
                    <i class="fas fa-comments"></i> Forum</button>
                <button type="button" class="btn btn-secondary" id="project_buttons">
                    <i class="fas fa-chart-line"></i> Statistics</button>
            </nav>
        </div>

        <div id="row_mobile">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="mobile_nav">
                <button type="button" class="btn btn-secondary" id="project_buttons">
                    <i class="fas fa-comments"></i>
                </button>
                <button type="button" class="btn btn-secondary" id="project_buttons">
                    <i class="fas fa-chart-line"></i>
                </button>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#Sprints"><i class="fas fa-bolt"></i>  Sprints</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#Tasks"><i class="far fa-sticky-note"></i>  Tasks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#Members"><i class="fas fa-users"></i> Members</a>
                </li> 
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Task -->
            <div class="sprint-task">
                <a data-toggle="collapse" href="#task-1"><i class="fas fa-sort-down"></i></a>
                <p>Make front page</p>
                <button class="btn">Claim task</button>
                <input type="checkbox">
                
                
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div id="task_info">
                <div id="task_description">
                        <h5><i class="fas fa-angle-right"></i>&nbsp;&nbsp;Making the front page is a top priority, there are already some mock-ups made, use them as a structure for this task.</h5>
                </div>
                <div id="task_images">
                    <h5>Annexed files:</h5>
                    <img src="res/task/mockup1.png">
                    <img src="res/task/mockup1.png">
                    <img src="res/task/mockup1.png">
                    <img src="res/task/mockup2.png">
                </div>
            </div>

            </div>     
        </div>
    </div>

@endif
    
@endsection