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
                @if($thread->user->image != NULL)
                    <img alt="Profile Image"src="{{$thread->user->image}}" width="150px">
                @else
                    <img alt="Profile Default Image"src="{{ asset('storage/'.'1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg')}}" width="150px">
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
        @include('partials.comment', ['project' => $project, 'thread' => $thread, 'comment' => $comment,'role'=> $role])
        @endforeach
        
        
        <div class="comment row" id="thread">
            
            @if(Auth::user()->image != NULL)
                <img alt="Profile Image" src="{{ asset('storage/'.Auth::user()->image)}}">
            @else						
                <img alt="Profile Default Image" src="{{ asset('storage/'.'1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg')}}">
            @endif

            <h6>{{\Auth::user()->username}}</h6>
            <div class="form_comment row">
                <form method="POST" action="{{ route('new_comment', ['id' => $project->id, 'thread_id' => $thread->id])}}">
                {{ csrf_field()}}
                
                <!-- <div class="col-8">-->
                    <label>Your post:</label>
                    <input type="text" class="form-control col-10" name="content" id="content">   
                    
                    
                    <button type="submit" class="btn btn-primary col-2">Send</button>
                    
                    <!--<div class="offset-2"></div>-->
                </form> 
            </div> 
                
        </div>
    </div>
</div>
@else

@endif

@endsection