@extends('layout.app')

@extends('title','Report_Form')

@extends('content')

@if(Auth::check())

<div id="container">
    <div id="options">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Project</a></li>            
            <li class="breadcrumb-item"><a href="#">Forum</a></li>            
            <li class="breadcrumb-item active">Report</li>            
        </ol>
    </div>
    <div id="overlay">
        <div class="jumbotron">
            <h3 class="display-3">Report a Comment</h3>
            <hr class="my-4">
            <div class="form-group">
                <label class="col-form-label" for="title">Summarize the reason of the report: </label>
                <input type="text" class="form-control" placeholder="Reason..." id="title">
            </div>
            <div class="form-group">
                <label class="col-form-label" for="description">Add a message to further explain the problem: </label>
                <textarea class="form-control" id="description" placeholder ="Description..." rows="5"></textarea>
            </div>
            <div id="lead">
                  <a class="btn btn-primary btn-lg" href="#" role="button">Report</a>
            </div>
        </div>   
    </div>
</div>

@else

@endif

@endsection