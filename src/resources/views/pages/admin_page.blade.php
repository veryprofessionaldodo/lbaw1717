<!DOCTYPE html> 
<html lang="{{ app()->getLocale() }}"> 
<head> 
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <!-- CSRF Token --> 
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
 
    <title>Proyekt Index</title> 
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script> 
    <script type="text/javascript" src={{ asset('js/admin.js') }} defer></script> 
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css')}}"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css')}}"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin_page.css')}}"> 
</head> 
<body> 
  <nav class="row"> 
    <a class="col-3" href="index.html">Proyekt</a> 
    <form class="col-6"> 
      <input class="form-control" type="text" placeholder="Search"> 
      <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button> 
    </form> 

    <a href="{{ route('logout')}}">logout</a>
    <!--<div id="user" class="col-3"> 
      <div id="mobile"> 
        <img src="res/profile/profile-pic.png"> 
      </div> 
    </div> -->
  </nav> 
 
  <div class="container-fluid" id="test"> 
    <div class="row"> 
      <aside class="col-12" id="navbar"> 
        <div class="row"> 
          <a class="col-6" href="{{route('admin_users', ['username' => Auth::user()->username])}}">
              User <br> Reports
          </a>
          <a id="active" class="col-6" href="{{route('admin_comments', ['username' => Auth::user()->username])}}"> 
           Comment <br> Reports
          </a> 
        </div> 
      </aside> 
 
      <section class="col-12"> 

        <div class="row"> 
          <div id="search_bar" class="col-12"> 
            <form> <!-- change styling of this input --> 
              <input class="form-control" type="text" placeholder="Search"> 
              <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button> 
            </form> 
          </div> 
        </div> 
 
        <div id="reports"> 

          @include('partials.reports_admin',['reports' => $reports])
          
        </div>
      </section>
    </div>
  </div>


</body>
</html>