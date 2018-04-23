@extends('layouts.app')

@section('title', 'Report_Form')

@section('content')

@if(Auth::check())

<div id="container">
    <div id="options">
        @if($type != 'USER')
        <ol class="breadcrumb">  
            <li class="breadcrumb-item"><a href="#">Project</a></li>            
            <li class="breadcrumb-item"><a href="#">Forum</a></li>
            <li class="breadcrumb-item active">Report</li>         
        </ol>
        @else
        @endif   
    </div>
    <div id="overlay">
        <div class="jumbotron">
            <h3 class="display-3">Report a Comment</h3>
            <hr class="my-4">
            <div class="form-group">
                <label class="col-form-label" for="description">Summarize the reason of the report: </label>
                <textarea name="report_summary" class="form-control" id="description" placeholder ="Description..." rows="5"></textarea>
            </div>
            <div id="lead">
            <a type={{$type}} user="{{Auth::user()->username}}" href="{{ route('user_report_form', ['username' => $user_reported->username])}}" class="btn btn-primary btn-lg" id="newReport-btn" >Report</a>
            </div>
        </div>   
    </div>
</div>

@else

@endif

@endsection