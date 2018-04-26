@extends('layouts.app')

@section('title', 'Report_Form')

@section('content')

@if(Auth::check())

<div id="container">
    <div id="options">
        @if($type != 'USER')
        <ol class="breadcrumb">  
            <li class="breadcrumb-item"><a href="{{ route('project', ['id' => $project_id])}}">Project</a></li>            
            <li class="breadcrumb-item"><a href="{{route('forum',['project_id' => $project_id])}}">Forum</a></li>
            <li class="breadcrumb-item active">Report</li>         
        </ol>
        @else
        @endif   
    </div>
    <div id="overlay">
        <div class="jumbotron">
            
            @if($type == 'USER')
                <h3 class="display-3">Report a User</h3>
            @elseif($type == 'COMMENT')
                <h3 class="display-3">Report a Comment</h3>
            @endif
            
            <hr class="my-4">
            <div class="form-group">
                <label class="col-form-label" for="description">Summarize the reason of the report: </label>
                <textarea name="report_summary" class="form-control" id="description" placeholder ="Description..." rows="5"></textarea>
            </div>
            <div id="lead">
            
            @if($type == 'USER')
                <a type={{$type}} user="{{Auth::user()->username}}" href="{{ route('create_user_report', ['username' => $user_reported->username])}}" class="btn btn-primary btn-lg" id="newReport-btn" >Report</a>
            @elseif($type == 'COMMENT')
                <a type={{$type}} user="{{Auth::user()->username}}" href="{{ route('create_comment_report', ['comment_id' => $comment->id])}}" class="btn btn-primary btn-lg" id="newReport-btn" >Report</a>
            @endif
            
            </div>
        </div>   
    </div>
</div>

@else

@endif

@endsection