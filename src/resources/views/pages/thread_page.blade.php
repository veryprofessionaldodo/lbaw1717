@extends('layouts.app')

@section('title', 'Forum Thread')

@section('content')

@if(Auth::check())
    <div id="container">

        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('project', ['id' => $project->id])}}">Project</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('forum',['project_id' => $project->id])}}">Forum</a>
            </li>
            <li class="breadcrumb-item active"></li>
        </ol>

        <div id="thread">
            <div class="row" id="header">
                <div class="col-3">
                    @if($thread->user->image != null)
                        <img src="{{$thread->user->image}}" width="150px">
                    @else
                        <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png" width="150px">
                    @endif
                    <figcaption>{{$thread->user->username}}</figcaption>
                </div>
                <div class="col-7">
                    <div id="issue">
                        <h2>{{$thread->name}}</h2>
                        <p>{{$thread->description}}</p>
                    </div>
                </div>
                <?php  
                    $date = new \DateTime($thread->date);
                ?>
                <div class="col-2">
                    <div class="date">
                        <p>{{$date->format('h:m')}}</p>
                        <p>{{$date->format('d/m/Y')}}</p>
                       <!-- <a class="btn" href="#">Report 
                            <i class="fas fa-flag"></i>
                        </a>-->
                        @if ($thread->canBeEdited(Auth::user()))
                        <a href="{{ route('edit_thread_form', ['id' => $project->id, 'thread_id' => $thread->id])}}"> <i class="fas fa-edit"></i> </a>
                        <button href="{{ route('deleteThread', ['id' => $project->id, 'thread_id' => $thread->id])}}" onclick="deleteThread(this)" id="{{$thread->id}}" class"deleteThread" ><i class="fas fa-trash"></i></button>
                        @endif 
                    </div>
                </div>
            </div>
            
            @foreach($comments as $comment)
                @include('partials.comment', ['project_id' => $project->id, 'thread' => $thread, 'comment' => $comment,'role'=> $role])
            @endforeach


        <div class="comment row">
            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png">
            <h6>{{\Auth::user()->username}}</h6>
            <form method="POST" action="{{ route('new_comment', ['id' => $project->id, 'thread_id' => $thread->id])}}">
                {{ csrf_field()}}
                
              <!-- <div class="col-8">-->
                    <label>Your post:</label>
                    <input type="text" class="form-control" name="content" id="content" placeholder="Write here...">    
               
                
                    <button type="submit" class="btn btn-secondary">Submit</button>
                
                <!--<div class="offset-2"></div>-->
            </form> 
            
            
        </div>
    </div>
@else

@endif

@endsection