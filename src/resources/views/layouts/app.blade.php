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
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('css/search.css') }}" rel="stylesheet">
    <link href="{{ asset('css/profile_page.css') }}" rel="stylesheet">
    <link href="{{ asset('css/projects_page.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <script type="text/javascript" src={{ asset('js/app.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/profile.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/project.js') }} defer></script>
  </head>
  
  <body>
    <nav class="row nav">
      <a class="col-3" href="{{ url('/')}}">Proyekt</a>

      <!--  FAZER ESTE FORM ATIVO -->
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
              @foreach($notifications as $notification)
                @if($notification->notification_type == 'invite')
                  <li class="row">
                    <div class="notification_content col-9">
                      <p>Invite to <strong>{{ $notification->name }}</strong> by <em>{{ $notification->username }}</em></p>
                    </div>
                    <div class="notification_options col-3">
                      <a class="btn"><i class="fas fa-check"></i></a>
                      <a class="btn"><i class="fas fa-times"></i></a>
                    </div>
                  </li>
                @elseif($notification->notification_type == 'comment')
                  <li class="row">
                    <div class="notification_content col-12">
                      <a href="#">A comment has been written in your thread</a>
                    </div>
                  </li>
                @elseif($notification->notification_type == 'commentreported')
                  <li class="row">
                    <div class="notification_content col-12">
                      <a href="#">Your comment has been reported</a>
                    </div>
                  </li>
                @elseif($notification->notification_type == 'promotion')
                  <li class="row">
                    <div class="notification_content col-12">
                      <a href="#">You were promoted in project <strong>{{ $notification->name }}</strong></a>
                    </div>
                  </li>
                @elseif($notification->notification_type == 'removedfromproject')
                  <li class="row">
                    <div class="notification_content col-12">
                      <a href="#">You were removed from project <strong>{{ $notification->name }}</strong></a>
                    </div>
                  </li>
                @endif
                <!-- Request? -->
              @endforeach
            </ul>
          </div>
        </div>
         
        @if (Auth::check())
          @if (Auth::user()->image != NULL)
            <img src="{{url(Auth::user()->image)}}">
          @else
            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png">
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
  
  </body>
</html>
