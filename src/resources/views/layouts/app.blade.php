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
    <!--<script type="text/javascript" src={{ asset('js/app.js') }} defer></script> -->
  </head>
  <body>
    <nav class="row">
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
        <button id="notification">
          <i class="fas fa-bell"></i>
        </button>
        <div class="dropdown">
          <div class="dropdown-content">
            <div class="modal">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Notifications</h5>

                    <!-- FAZER ISTO -->
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                      <p><i class="far fa-paper-plane"></i>&nbsp; &nbsp;jotacl has invited you to project Management App</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-success">Accept</button>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Decline</button>
                    </div>
                    <div class="modal-body">
                      <p><i class="far fa-paper-plane"></i>&nbsp; &nbsp;jotacl has promoted you to Project Coordinator of the project Management App</p>
                    </div>-->
                </div>
              </div>
            </div>
          </div>
        </div>  
        @if (Auth::check())
          @if (Auth::user()->image == NULL)
            <img src="res/profile/profile-pic.png">
          @else
            <img src="{{url(Auth::user()->image)}}">
          @endif
        @endif
      </div>
    </div>
  </nav>

  @yield('content');
  
  </body>
</html>
