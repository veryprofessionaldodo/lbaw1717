<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Project;
use App\Category;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserController extends Controller {

    public function showProfile(string $username) {
	    if (!Auth::check()) return redirect('/login');
        $this->authorize('list', Project::class);

        $projects = Auth::user()->userProjects();

        $requests = request()->headers->all();
        print_r($requests);

        if(request()->ajax()) {
            $viewHTML = view('partials.user_projects', ['projects' => $projects])->render();
            echo $viewHTML;
            return response()->json(array('success' => true, 'html' => $viewHTML));
        }

        $notifications = Auth::user()->userNotifications();

        $taskCompletedWeek = Auth::user()->taskCompletedThisWeek()[0];
        $taskCompletedMonth = Auth::user()->taskCompletedThisMonth()[0];
        $sprintsContributedTo = Auth::user()->sprintsContributedTo()[0];
  
        return view('pages/user_profile', ['projects' => $projects, 'taskCompletedWeek' => $taskCompletedWeek, 'taskCompletedMonth' => $taskCompletedMonth, 
        'sprintsContributedTo' => $sprintsContributedTo, 'notifications' => $notifications]);
      
    }
    
    public function getUserProjects(int $n) {
        $projects = Auth::user()->userProjects();
        
        return view('partials.user_projects', ['projects' => $projects]);
    }

	public function showAdminPage(string $username){
		if (!Auth::check()) return redirect('/login');

		return view('pages/admin_page');
	}

    /**
        Returns the form to edit a profile
    */
    public function editProfileForm(Request $request) {
        if (!Auth::check()) return redirect('/login');
            
        $viewHTML = view('partials.edit_profile')->render();
        return response()->json(array('success' => true, 'html' => $viewHTML));
    }

    /**
        Returns the form to create a new project
    */
    public function createProjectForm(Request $request) {
        if (!Auth::check()) return redirect('/login');

        $categories = Category::all();
            
        $viewHTML = view('partials.create_project_form',['categories' => $categories])->render();
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