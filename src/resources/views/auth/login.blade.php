<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proyekt Sign In</title>
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sign_form.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css')}}">
</head>
<body>
    <nav class="row">
        <a class="col-3" href="index.html">Proyekt</a>
        <form class="col-6">
            <input class="form-control mr-sm-2" type="text" placeholder="Search">
            <button class="btn btn-primary my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </nav>

    <section>
        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <h2>Sign In</h2>

            <input type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
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
            <hr>
            <button class="btn btn-info">Sign In with Facebook</button>
            <button class="btn btn-info">Sign In with Github</button>
            <p>If you don't have an account, </p>
            <a href="{{ route('register') }}">please sign up.</a>
        </form>
    </section>

    <footer>
        <div id="contacts">
            <h6>Contacts</h6>
            <p>(+351)255255255</p>
        </div>

        <div id="info">
            <a href="#">Terms of use</a>
        </div>
    </footer>
</body>
</html>