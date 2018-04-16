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

<!--
            <div class="row comment table-dark">
                <div class="col-2">
                    <img src="res/forum/bee.jpg" id="profile_pic">
                    <figcaption>BeeMargarida</figcaption>
                </div>
                <div class="col-8">
                    <p>You have plenty of infomation on the w3schools website, maybe head over there!.</p>
                </div>
                <div class="col-2">
                    <div class="date">
                        <p>22:31
                            <a class="btn" href="report_page.html">Report 
                                <i class="fas fa-flag"></i>
                            </a>
                        </p>
                        <p>20/02/2018</p>

                    </div>
                </div>
            </div>

            <div class="row comment table-dark">
                <div class="col-2">
                    <img src="res/forum/dodo.jpg" id="profile_pic">
                    <figcaption>ragingdodo</figcaption>
                </div>
                <div class="col-8">
                    <p>
                        <strong>@Sportelona</strong>, come on, you really should know grids by now... Just ignore him,
                        <strong>@BeeMargarida!</strong>
                    </p>
                </div>
                <div class="col-2">
                    <div class="date">
                        <p>22:35
                            <a class="btn" href="report_page.html">Report 
                                <i class="fas fa-flag"></i>
                            </a>
                        </p>
                        <p>20/02/2018</p>
                    </div>
                </div>
            </div>

            <div class="row comment table-dark">
                <div class="col-2">
                    <img src="res/forum/css_master.jpg" id="profile_pic">
                    <figcaption>designing101</figcaption>
                </div>
                <div class="col-8">
                    <p>I know some tricks I can teach you.</p>
                </div>
                <div class="col-2">
                    <div class="date">
                        <p>22:48
                            <a class="btn" href="report_page.html">Report
                                <i class="fas fa-flag"></i>
                            </a>
                        </p>
                        <p>20/02/2018</p>
                    </div>
                </div>
            </div>

            <div class="row comment table-dark">
                    <div class="col-2">
                        <img src="res/forum/sloth.jpg" id="profile_pic">
                        <figcaption>slacker404</figcaption>
                    </div>
                    <div class="col-8">
                        <p>Sorry, I'm late to this issue... Still need help?</p>
                    </div>
                    <div class="col-2">
                        <div class="date">
                            <p>02:48
                                <a class="btn" href="report_page.html">Report
                                    <i class="fas fa-flag"></i>
                                </a>
                            </p>
                            <p>21/02/2018</p>
                        </div>
                    </div>
                </div>

            <div class="row comment table-dark">
                <div class="col-2">
                    <img src="res/forum/squirtle.jpeg" id="profile_pic">
                    <figcaption>lues</figcaption>
                </div>
                <div class="col-8">
                    <label>Your post:</label>
                    <input type="text" class="form-control" placeholder="Write here...">    
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-secondary">Submit</button>
                </div>
                <div class="offset-2"></div>
            </div>
        </div>


    
    </div>
    <footer>
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