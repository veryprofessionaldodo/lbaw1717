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

	
		$notifications = Auth::user()->userNotifications();
		/*foreach($notifications as $notification){
				echo $notification->notification_type;
				echo $notification->username;
				echo $notification->name;
		}*/
	
		$projects = Auth::user()->userProjects(0);
	
		$taskCompletedWeek = Auth::user()->taskCompletedThisWeek()[0];
		$taskCompletedMonth = Auth::user()->taskCompletedThisMonth()[0];
		$sprintsContributedTo = Auth::user()->sprintsContributedTo()[0];
		  
		return view('pages/user_profile', ['projects' => $projects, 'taskCompletedWeek' => $taskCompletedWeek, 'taskCompletedMonth' => $taskCompletedMonth, 'sprintsContributedTo' => $sprintsContributedTo, 'notifications' => $notifications]);
	
			
		
      
	}

	public function showAdminPage(string $username){
		if (!Auth::check()) return redirect('/login');

		return view('pages/admin_page');
	}

	/**
		Udpate the users profile
	*/
	public function update(Request $request) {

	}

}

?>