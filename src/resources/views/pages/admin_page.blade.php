
<!DOCTYPE html> 
<html lang="{{ app()->getLocale() }}"> 
<head> 
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <!-- CSRF Token --> 
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
 
    <title>Proyekt Index</title> 
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script> 
    <script type="text/javascript" src={{ asset('js/app.js') }} defer></script> 
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css')}}"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('css/index.css')}}"> 
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
    <div id="user" class="col-3"> 
      <input type="checkbox" id="hamburger">  
      <label class="hamburger" for="hamburger"></label> 
      <div id="mobile"> 
        <img src="res/profile/profile-pic.png"> 
      </div> 
    </div> 
  </nav> 
 
  <div class="container-fluid" id="test"> 
    <div class="row"> 
      <aside class="col-12" id="navbar"> 
        <div class="row"> 
          <div class="col-6"> 
            <a href="#">User <br> Reports</a> 
          </div> 
          <div id="active" class="col-6"> 
            <a href="">Comment <br> Reports</a> 
          </div> 
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
          <div class="report report_comment col-12"> 
            <div class="report_principal_info"> 
              <p>Comment by user <a href="#">halo_1997</a> has been reported</p> 
              <a href="#">More info</a> 
              <div class="options"> 
                <button class="btn">Delete Comment</button> 
                <button class="btn">Delete User</button> 
                <button class="btn">Dismiss Report</button> 
              </div> 
            </div> 
            <div class="report_details"> 
              <p><span>1/3/2018</span> , by user <a href="#">john_doe_09</a> on project <strong>Goat_Simulator</strong></p> 
              <a href="#">Link to Comment</a> 
              <h4>Comment Content: </h4> 
              <p>"Michael Jakson did horrible music!"</p> 
              <h4>Reason: </h4> 
              <p>Offensive language in a working environment</p> 
              <h4>Details: </h4> 
              <p>Nullam finibus sagittis elit, vel molestie felis efficitur sit amet. Vestibulum non placerat ipsum. Maecenas eu magna convallis eros tristique placerat. Duis malesuada malesuada malesuada. Phasellus vestibulum sagittis lacus, a consequat nisi porta a. Donec ante nulla, facilisis malesuada pharetra auctor, volutpat non velit. Vestibulum sagittis vitae sapien fringilla cursus. Curabitur bibendum tincidunt varius. Mauris ac mattis quam, nec ornare nulla. Cras et lorem porttitor, commodo tellus nec, egestas diam.</p> 
