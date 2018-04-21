@extends('layouts.app')

@section('title', 'Forum Thread')

@section('content')

@if(Auth::check())
    <div id="container">

        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Project</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">Forum</a>
            </li>
            <li class="breadcrumb-item active"></li>
        </ol>

        <div id="thread">
            <div class="row" id="header">
                <div class="col-3">
                    <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png" height="150px" width="150px">
                    <figcaption>sportelona</figcaption>
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
                        <a class="btn" href="report_page.html">Report 
                            <i class="fas fa-flag"></i>
                        </a>
                    </div>

                </div>
            </div>

            @each('partials.comment', $comments, 'comment')

        </div>
    </div>
@else

@endif

@endsection