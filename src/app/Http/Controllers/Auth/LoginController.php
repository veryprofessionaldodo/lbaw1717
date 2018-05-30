<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function username() {
        return 'username';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout', 'getLogout');
    }

    public function logout(Request $request) {
        $tmp = $request->session()->has('disabled');
        Auth::logout();
        Session::flush();
        if($tmp){
            \Session::flash('flash_message_danger','Your account is disabled'); //<--FLASH MESSAGE
        }
        else {
            Session::flash('flash_message','You have been logged out'); //<--FLASH MESSAGE
        }
        return redirect('/');
    }


}
