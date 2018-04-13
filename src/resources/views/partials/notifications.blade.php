<!--@extends('layouts.app')

@section('notifications')
-->
<ul class="nav navbar-nav navbar-right">
  <li class="dropdown">
    <button id="notification" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-bell"></i>
    </button>
    <ul class="dropdown-menu notify-drop">
      <div class="notify-drop-title">
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-6">Bildirimler (<b>2</b>)</div>
          <div class="col-md-6 col-sm-6 col-xs-6 text-right"><a href="" class="rIcon allRead" data-tooltip="tooltip" data-placement="bottom" title="tümü okundu."><i class="fa fa-dot-circle-o"></i></a></div>
        </div>
      </div>
      <!-- end notify title -->
      <!-- notify content -->
      <div class="drop-content">
        <li>
          <div class="col-md-3 col-sm-3 col-xs-3"><div class="notify-img"><img src="http://placehold.it/45x45" alt=""></div></div>
          <div class="col-md-9 col-sm-9 col-xs-9 pd-l0"><a href="">Ahmet</a> yorumladı. <a href="">Çicek bahçeleri...</a> <a href="" class="rIcon"><i class="fa fa-dot-circle-o"></i></a>
          
          <hr>
          <p class="time">Şimdi</p>
          </div>
        </li>
        <li>
          <div class="col-md-3 col-sm-3 col-xs-3"><div class="notify-img"><img src="http://placehold.it/45x45" alt=""></div></div>
          <div class="col-md-9 col-sm-9 col-xs-9 pd-l0"><a href="">Ahmet</a> yorumladı. <a href="">Çicek bahçeleri...</a> <a href="" class="rIcon"><i class="fa fa-dot-circle-o"></i></a>
          <p>Lorem ipsum sit dolor amet consilium.</p>
          <p class="time">1 Saat önce</p>
          </div>
        </li>
        <li>
          <div class="col-md-3 col-sm-3 col-xs-3"><div class="notify-img"><img src="http://placehold.it/45x45" alt=""></div></div>
          <div class="col-md-9 col-sm-9 col-xs-9 pd-l0"><a href="">Ahmet</a> yorumladı. <a href="">Çicek bahçeleri...</a> <a href="" class="rIcon"><i class="fa fa-dot-circle-o"></i></a>
          <p>Lorem ipsum sit dolor amet consilium.</p>
          <p class="time">29 Dakika önce</p>
          </div>
        </li>
        <li>
          <div class="col-md-3 col-sm-3 col-xs-3"><div class="notify-img"><img src="http://placehold.it/45x45" alt=""></div></div>
          <div class="col-md-9 col-sm-9 col-xs-9 pd-l0"><a href="">Ahmet</a> yorumladı. <a href="">Çicek bahçeleri...</a> <a href="" class="rIcon"><i class="fa fa-dot-circle-o"></i></a>
          <p>Lorem ipsum sit dolor amet consilium.</p>
          <p class="time">Dün 13:18</p>
          </div>
        </li>
        <li>
          <div class="col-md-3 col-sm-3 col-xs-3"><div class="notify-img"><img src="http://placehold.it/45x45" alt=""></div></div>
          <div class="col-md-9 col-sm-9 col-xs-9 pd-l0"><a href="">Ahmet</a> yorumladı. <a href="">Çicek bahçeleri...</a> <a href="" class="rIcon"><i class="fa fa-dot-circle-o"></i></a>
          <p>Lorem ipsum sit dolor amet consilium.</p>
          <p class="time">2 Hafta önce</p>
          </div>
        </li>
      </div>
      <div class="notify-drop-footer text-center">
        <a href=""><i class="fa fa-eye"></i> Tümünü Göster</a>
      </div>
    </ul>
  </li>
</ul>
