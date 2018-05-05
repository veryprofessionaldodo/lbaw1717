<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('css/search.css') }}" rel="stylesheet">
    
    <link href="{{ asset('css/profile_page.css') }}" rel="stylesheet">
    <link href="{{ asset('css/projects_page.css') }}" rel="stylesheet">
    <link href="{{ asset('css/forum.css') }}" rel="stylesheet">
    <link href="{{ asset('css/forum_thread.css') }}" rel="stylesheet">
    <link href="{{ asset('css/statistics.css') }}" rel="stylesheet">
    <link href="{{ asset('css/project_settings.css') }}" rel="stylesheet">
    

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>

    <!-- only for bootstrap -->
    <script defer src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script defer src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
    <!-- only for bootstrap -->

    <script type="text/javascript" src={{ asset('js/app.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/profile.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/project.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/forum.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/report.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/admin.js') }} defer></script> 
  </head>
  
  <body>
    <nav class="row nav">
      <a class="col-3" href="{{ url('/')}}">Proyekt</a>

      <form class="col-6" method="POST" action="{{ route('search') }}">
        {{ csrf_field() }}

        <input class="form-control" name="search" type="text" placeholder="Search">

        <button class="btn btn-primary" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </form>

      <div class="user col-3">  

        <div id="notifications">

          <label class="hamburger" for="hamburger2"></label>

          <div id="notifications_box">
            <ul>

              @include('partials.notifications')
              
            </ul>
          </div>
        </div>
         
        @if (Auth::check())
          @if (Auth::user()->image != NULL)
          <img src="{{ asset('storage/'. Auth::user()->image)}}">
          @else
            <img src="{{ asset('storage/'.'1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg')}}">
          @endif
        @endif

        <div id="profile_options">
          <ul>
            <li><a href="{{ route('user_profile', ['username' => Auth::user()->username])}}">View Profile</a></li>
            <li><a href="{{ route('logout') }}">Logout</a></li>
          </ul>
        </div>
        
      </nav>

  @yield('content')
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
