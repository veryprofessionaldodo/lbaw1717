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
    <link href="{{ asset('css/profile_page.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <script type="text/javascript" src={{ asset('js/app.js') }} defer></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" defer></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" defer></script>
  </head>
  <body>
    <nav class="row nav">
      <a class="col-3" href="{{ url('/')}}">Proyekt</a>

      <!--  FAZER ESTE FORM ATIVO -->
      <form class="col-6">
        <input class="form-control" type="text" placeholder="Search">
        <button class="btn btn-primary" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </form>

      <div id="user" class="col-3">
        <input type="checkbox" id="hamburger">
        <label class="hamburger" for="hamburger"></label>
        <div id="mobile">
        
        <ul class="nav nav-pills">
          <li class="nav-item dropdown">
            <a href="#" class="nav-link text-light" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-bell"></i>
            </a>
            <ul class="dropdown-menu float-lg-left">
              <li class="head text-light bg-dark">
                <div class="row">
                  <div class="col-lg-12 col-sm-12 col-12">
                    <span>Notifications (3)</span>
                    <a href="" class="float-right text-light">Mark all as read</a>
                  </div>
              </li>
              <li class="notification-box">
                <div class="row">
                  <div class="col-lg-8 col-sm-8 col-8">
                    <strong class="text-info">David John</strong>
                    <div>
                      Lorem ipsum dolor sit amet, consectetur
                    </div>
                    <small class="text-warning">27.11.2015, 15:00</small>
                  </div>  
                  <div class="col-lg-3 col-sm-3 col-3 text-center">
                    <button>Check</button>
                    <button>Not</button>
                  </div>  
                </div>
              </li>
              <li class="notification-box bg-gray">
                <div class="row">
                  <div class="col-lg-3 col-sm-3 col-3 text-center">
                    <img src="/demo/man-profile.jpg" class="w-50 rounded-circle">
                  </div>    
                  <div class="col-lg-8 col-sm-8 col-8">
                    <strong class="text-info">David John</strong>
                    <div>
                      Lorem ipsum dolor sit amet, consectetur
                    </div>
                    <small class="text-warning">27.11.2015, 15:00</small>
                  </div>    
                </div>
              </li>
              <li class="notification-box">
                <div class="row">
                  <div class="col-lg-3 col-sm-3 col-3 text-center">
                    <img src="/demo/man-profile.jpg" class="w-50 rounded-circle">
                  </div>    
                  <div class="col-lg-8 col-sm-8 col-8">
                    <strong class="text-info">David John</strong>
                    <div>
                      Lorem ipsum dolor sit amet, consectetur
                    </div>
                    <small class="text-warning">27.11.2015, 15:00</small>
                  </div>    
                </div>
              </li>
              <li class="footer bg-dark text-center">
                <a href="" class="text-light">View All</a>
              </li>
            </ul>
          </li>
        </ul>


         
          @if (Auth::check())
            @if (Auth::user()->image != NULL)
              <img src="{{url(Auth::user()->image)}}">
            @else
              <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png">
            @endif
          @endif
        </div>
      </nav>

  @yield('content')
  
  </body>
</html>
