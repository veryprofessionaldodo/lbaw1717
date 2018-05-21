@extends('layouts.app')

@section('title', 'Admin Page')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin_page.css')}}"> 

@if(Auth::check())
 
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
          <div id="search_bar_admin" class="col-12"> 
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

  @else

  @endif
  
  @endsection