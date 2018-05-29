<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller {

	use AuthenticatesUsers;

	public function showLandingPage() {
        if(!Auth::check()){
            return view('pages/landing_page');
        }

        if(Auth::user()->isAdmin()){
            return redirect()->route('admin_page', ['username' => Auth::user()->username]);
        }
        else {
            return redirect()->route('user_profile', ['username' => Auth::user()->username]);
        }
	}

	 /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}

?>