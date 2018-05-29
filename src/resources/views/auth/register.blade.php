<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Proyekt Sign Up</title>
  <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/sign_form.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css')}}">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand" href="{{ url('/')}}">Proyekt</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
          
          <form class="form-inline my-2 my-lg-0 mr-auto" method="POST" action="{{ route('search') }}">
          {{ csrf_field() }}
          <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-primary my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
          </form>
  
      </div>
  </nav>

  <section>
    <form method="POST" action="{{ route('register') }}">
      {{ csrf_field() }}

      <h2>Sign Up</h2>

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

      <hr>
      <button class="btn btn-info">Sign Up with Facebook</button>
      <button class="btn btn-info">Sign Up with Github</button>
      <p>If already have an account, </p>
      <a href="{{ route('login') }}">please sign in.</a>
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