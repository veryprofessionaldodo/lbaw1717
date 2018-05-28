<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {   
        if(Auth::guard($guard)->check() && Auth::user()->isDisable()){

            //$file = 'js/disable_user_message.js';
            //echo "<script type='text/javascript' src={{ asset($file) }} defer></script>";
            //echo '<script>alert("hello");</script>';
            //echo "poop";

            /*echo '<script type="text/javascript" src="https://unpkg.com/sweetalert/dist/sweetalert.min.js">
            setTimeout(function () { swal("You have been banned from this Website by the administrator !", {
                icon: "error",
            });}, 1000);</script>';*/

            //Auth::logout();
            //return response()->view('layouts/disable_user');
            //return something saying user cant access the website
        }
        else if(Auth::guard($guard)->check() && Auth::user()->isAdmin()){
            return redirect()->action('AdminController@showAdminPage', Auth::user()->username);
        }
        else if (Auth::guard($guard)->check()){
            return redirect()->action('User\UserController@showProfile',Auth::user()->username);
        }
        return $next($request);
    }
}
