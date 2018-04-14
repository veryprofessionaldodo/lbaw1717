<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserController extends Controller {


    public function showProfile(string $n) {
	   if (!Auth::check()) return redirect('/login');

        //$this->authorize('list', Project::class);

        $notifications = Auth::user()->userNotifications();
        /*foreach($notifications as $notification){
        	echo $notification->notification_type;
        	echo $notification->username;
        	echo $notification->name;
        }*/

        $numProjects = Auth::user()->projects()->count();
		$projects = Auth::user()->userProjects((int)$n);
        $taskCompletedWeek = Auth::user()->taskCompletedThisWeek()[0];
        $taskCompletedMonth = Auth::user()->taskCompletedThisMonth()[0];
        $sprintsContributedTo = Auth::user()->sprintsContributedTo()[0];
  
		return view('pages/user_profile', ['projects' => $projects, 'taskCompletedWeek' => $taskCompletedWeek, 'taskCompletedMonth' => $taskCompletedMonth, 'sprintsContributedTo' => $sprintsContributedTo, 'notifications' => $notifications, 'n' => (int)$n, 'numProjects' => $numProjects]);
	}

    /**
        Returns the form to edit a profile
    */
    public function editProfileForm(Request $request) {
        $viewHTML = view('partials.edit_profile')->render();
        return response()->json(array('success' => true, 'html' => $viewHTML));
    }

	/**
		Udpate the users profile
	*/
	public function editProfileAction(Request $request) {
        if (!Auth::check()) return redirect('/login');

        $user = Auth::user();

        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->image = $request->input('image');

        $user->save();

        return route('user_profile', [Auth::user()->username]);
	}

}

?>