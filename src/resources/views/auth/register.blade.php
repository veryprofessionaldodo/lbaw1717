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
  <nav class="row">
    <a class="col-3" href="index.html">Proyekt</a>
    <form class="col-6">
        <input class="form-control mr-sm-2" type="text" placeholder="Search">
        <button class="btn btn-primary my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
    </form>
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