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
                        <a class="btn" href="#">Report 
                            <i class="fas fa-flag"></i>
                        </a>
                    </div>

                </div>
            </div>

            @each('partials.comment', $comments, 'comment')


        <div class="row comment table-dark">
            <div class="col-2">
                <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png" id="profile_pic">
                <figcaption>{{\Auth::user()->username}}</figcaption>
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