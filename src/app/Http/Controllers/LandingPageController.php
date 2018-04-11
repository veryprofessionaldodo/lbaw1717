<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LandingPageController extends Controller {

	use AuthenticatesUsers;

	public function showLandingPage() {
		return view('pages/landing_page');
	}

	 /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/api/users' + + $request->session('username');

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