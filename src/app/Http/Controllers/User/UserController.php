<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserController extends Controller {


	public function showProfile(string $username) {
		if (!Auth::check()) return redirect('/login');

        //$this->authorize('list', Project::class);

        //$projects = Auth::user()->projects()->get(); 
		$projects = Auth::user()->userProjects($username, 0);
        foreach($projects as $project){
        	echo $project->name;
        }

		return view('pages/user_profile', ['projects' => $projects]);
	}

	/**
		Udpate the users profile
	*/
	public function update(Request $request) {

	}

}

?>