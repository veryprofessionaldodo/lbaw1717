<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Proyekt Index</title>
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <script type="text/javascript" src={{ asset('js/landing_page.js') }} defer></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/index.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css')}}">
</head>
<body>
<nav class="row">
    <a class="col-3" href="#">Proyekt</a>
    <form class="col-6">
        <input class="form-control" type="text" placeholder="Search">
        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
    </form>
</nav>

<section>
    <div id="text">
        <h2>Project Management Project</h2>
        <p>Welcome! Proyekt is a platform for management and productivity.</p>
        <p>Feel free to join us!</p>
        <a href="#">Facebook</a> <!-- Insert Icon -->
    </div>

    <div id="sign_mobile">
        <button class="btn btn-primary">Sign Up</button>
        <div></div>
        <button class="btn btn-primary">Sign In</button>
    </div>

    <div id="sign">
        <div id="sign_buttons">
            <a >Sign In</a>
            <a id="selected">Sign Up</a>
        </div>
        <form method="POST" action="{{ route('login') }}" id="sign_in" class="hide">
            <h3>Welcome! Back to work!</h3>
            {{ csrf_field() }}
            <input type="text" class="form-control" name="username" placeholder="Username" value="{{ old('username') }}" required autofocus>
            @if ($errors->has('username'))
                <span class="error">
                {{ $errors->first('username') }}
                </span>
            @endif
            <input type="password" class="form-control" name="password" placeholder="Password">
            @if ($errors->has('password'))
                <span class="error">
                {{ $errors->first('password') }}
                </span>
            @endif

            <button type="submit" class="btn btn-primary">Sign In</button>
            <button class="btn btn-info">Sign In with Facebook</button>
            <button class="btn btn-info">Sign In with Github</button>
        </form>
        <form method="POST" action="{{ route('register') }}" id="sign_up" >
            {{ csrf_field() }}

            <h3>We would be thrilled if you joined our family!</h3>
            <input type="text" class="form-control" name="username" placeholder="Username" required>
            @if ($errors->has('username'))
               <span class="error">
                   {{ $errors->first('username') }}
               </span>
            @endif

            <input type="text" class="form-control" name="name" placeholder="Name" required>
            @if ($errors->has('name'))
                <span class="error">
                    {{ $errors->first('name') }}
                </span>
            @endif

            <input type="email" class="form-control" name="email" placeholder="Email" required>
            @if ($errors->has('email'))
                <span class="error">
                    {{ $errors->first('email') }}
                </span>
            @endif

            <input type="password" class="form-control" name="password" placeholder="Password" required>
            @if ($errors->has('password'))
                <span class="error">
                    {{ $errors->first('password') }}
                </span>
            @endif

            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>

            <button type="submit" class="btn btn-primary">Sign Up</button>
            <button class="btn btn-info">Sign Up with Facebook</button>
            <button class="btn btn-info">Sign Up with Github</button>
        </form>
    </div>
</section>

@extends('partials.footer');

</body>
</html>