@extends('layouts.app')

@section('title', 'Forum')

@section('content')

@if(Auth::check())
    <div id="container">

        <div id="options">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('project', ['id' => $project->id])}}">Project</a></li>            
                <li class="breadcrumb-item active">Forum</li>            
            </ol>     
        </div>

        <div id="header">
            <h1>Forum</h1>
            <a class="btn btn-info" id="new_thread_form" href="{{ route('new_thread_form', ['id' => $project->id])}}"><i class="fas fa-plus"></i> New Thread</a>               
        </div>
        
        <div id="threads">
            <table class="table table-hover" width="200px" height="200%">
                <tr id="descriptions_table">
    	            <th class="desc" scope="col">Title</th>
                    <th class="desc" scope="col">User</th>
                    <th class="desc" scope="col">Date</th>
                </tr>
                
                @foreach($threads as $thread)
			        @include('partials.thread', ['thread' => $thread])
                @endforeach
                
            </table>
        </div>

    </div>

@else



@endif

@endsection