<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Project;
use App\Category;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

use Illuminate\Http\Request;

class UserController extends Controller {

    public function showProfile(string $username) {
	    if (!Auth::check()) return redirect('/login');
        $this->authorize('list', Project::class);

        try {
            $user = User::where('username',$username)->get();
            $projects = $user[0]->userProjects();
            $public_projects = $user[0]->userPublicProjects();
        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
        
        // AJAX PAGINATION - NOT WORKING
        // $requests = request()->headers->all();
        // print_r($requests);

        // if(request()->ajax()) {
        //     $viewHTML = view('partials.user_projects', ['projects' => $projects])->render();
        //     echo $viewHTML;
        //     return response()->json(array('success' => true, 'html' => $viewHTML));
        // }]);

        try {
            $notifications = Auth::user()->userNotifications();

            $taskCompletedWeek = $user[0]->taskCompletedThisWeek()[0];
            $taskCompletedMonth = $user[0]->taskCompletedThisMonth()[0];
            $sprintsContributedTo = $user[0]->sprintsContributedTo()[0];
            
            return view('pages/user_profile', ['projects' => $projects,'public_projects' => $public_projects, 'taskCompletedWeek' => $taskCompletedWeek, 'taskCompletedMonth' => $taskCompletedMonth, 
            'sprintsContributedTo' => $sprintsContributedTo, 'notifications' => $notifications, 'user' => $user[0]]);

        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }

      
    }
    
    public function getUserProjects(int $n) {
        try {
            $projects = Auth::user()->userProjects();
            return view('partials.user_projects', ['projects' => $projects]);

        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
        
    }

	public function showAdminPage(string $username){
		if (!Auth::check()) return redirect('/login');

		return view('pages/admin_page');
	}

    /**]);
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

        try {
            $categories = Category::all();
            $viewHTML = view('partials.create_project_form',['categories' => $categories])->render();
            return response()->json(array('success' => true, 'html' => $viewHTML));

        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
            
    }

	/**
		Udpate the users profile
	*/
	public function editProfileAction(Request $request) {
        if (!Auth::check()) return redirect('/login');
        $user = Auth::user();
        try {
            $user->name = $request->user_name;
            $user->username = $request->user_username;
            $user->email = $request->user_email;
            
            if($request->hasFile('user_image')){
                $file = $request->file('user_image');
                echo $file;
                $user->image = $request->file('user_image')->store('public');              
            }
    
    
            $user->save();
            return back();

        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
	}
}

?>