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
                    <img src="res/forum/liedson.jpg" height="150px" width="150px">
                    <figcaption>sportelona</figcaption>
                </div>
                <div class="col-7">
                    <div id="issue">
                        <h2>{{$thread->name}}</h2>
                        <p>{{$thread->description}}</p>
                    </div>
                </div>
                <div class="col-2">
                    <div class="date">
                        <p>22:25
                            <a class="btn" href="report_page.html">Report 
                                <i class="fas fa-flag"></i>
                            </a>
                        </p>
                        <p>20/02/2018</p>
                    </div>

                </div>
            </div>

            @each('partials.comment', $comments, 'comment')



        <div class="row comment table-dark">
            <div class="col-2">
                <img src="res/forum/squirtle.jpeg" id="profile_pic">
                <figcaption>lues</figcaption>
            </div>
            <form method="POST" action="/projects/{{ $project -> id }}/threads/{{ $thread -> id}}/comments">
                {{ csrf_field()}}
                
                <div class="col-8">
                    <label>Your post:</label>
                    <input type="text" class="form-control" name="content" id="content" placeholder="Write here...">    
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-secondary">Submit</button>
                </div>
                <div class="offset-2"></div>
            </form> 
            
        </div>


    
    </div>
  <!--  <footer>
        <div id="contacts">
            <h6>Contacts</h6>
            <p>(+351)255255255</p>
        </div>

        <div id="info">
            <a href="#">Terms of use</a>
        </div>
    </footer>
-->
@else

@endif

@endsection