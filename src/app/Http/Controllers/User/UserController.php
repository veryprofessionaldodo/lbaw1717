<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserController extends Controller {


	public function showProfile(String $username) {
		return view('pages/user_profile');
	}

	/**
		Udpate the users profile
	*/
	public function update(Request $request) {

	}

}

?>