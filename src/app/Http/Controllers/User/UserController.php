<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Project;
use App\Category;
use App\Notification;
use App\Invite;

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
            $user = User::where('username',$username)->first();
            $projects = $user->userProjects();
            $public_projects = $user->userPublicProjects();

            $taskCompletedWeek = $user->taskCompletedThisWeek()[0];
            $taskCompletedMonth = $user->taskCompletedThisMonth()[0];
            $sprintsContributedTo = $user->sprintsContributedTo()[0];
            
            return view('pages/user_profile', ['projects' => $projects,'public_projects' => $public_projects, 'taskCompletedWeek' => $taskCompletedWeek, 'taskCompletedMonth' => $taskCompletedMonth, 
            'sprintsContributedTo' => $sprintsContributedTo, 'user' => $user]);

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
                $user->image = $request->file('user_image')->store('');              
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
    
    /*
    Deletes notification
   */
    public function dismissNotification($notification_id){
        if (!Auth::check()) return redirect('/login');

        try {

            $notification = Notification::find($notification_id);
            $notification->delete();

            return response()->json(array('success' => true, 'notification_id' => $notification_id));
            
        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
    }

    /*
    Accepts invite to project and deletes invite and notification
   */
    public function acceptInviteNotification($notification_id){
        if (!Auth::check()) return redirect('/login');

        try {

            $notification = Notification::find($notification_id);

            $invite = Invite::where([['project_id','=',$notification->project_id],['user_who_invited_id','=',$notification->user_action_id]])->first(); 
            $invite->delete();

            DB::table('project_members')->insert(
                ['user_id' => $notification->user_id , 'project_id' =>$notification->project_id , 'iscoordinator' => FALSE]
              );

            $notification->delete();

            return response()->json(array('success' => true, 'notification_id' => $notification_id));
            
        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        } 
    }

    /*
    Rejects invite to project and deletes invite and notification
   */
    public function rejectInviteNotification($notification_id){
        if (!Auth::check()) return redirect('/login');

        try {

            $notification = Notification::find($notification_id);

            $invite = Invite::where([['project_id','=',$notification->project_id],['user_who_invited_id','=',$notification->user_action_id]])->first(); 
            $invite->delete();

            $notification->delete();

            return response()->json(array('success' => true, 'notification_id' => $notification_id));
            
        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        } 
    }

    public function requestJoinProject($project_id){
        if (!Auth::check()) return redirect('/login');

        try {
            $project = Project::find($project_id);

            $request = Invite::where([['user_invited_id','=',Auth::user()->id],['project_id','=',$project_id],['user_who_invited_id','=',null]])->get();

            $invite = Invite::where([['user_invited_id','=',Auth::user()->id],['project_id','=',$project_id]])->get();

            if($invite->isEmpty() && $request->isEmpty()){  //if it doesnt already exist a request or invite of this project to the auth
                $request_created = new Invite();

                $request_created->user_invited_id = Auth::user()->id;
                $request_created->project_id = $project_id;
                $request_created->user_who_invited_id = null ;
    
                $request_created->save();

                return response()->json(array('success' => true, 'project_name' => $project->name));
            }else if(!$invite->isEmpty()){
                return response()->json(array('success' => false, 'reason' => 'invite' ,'project_name' => $project->name));
            }else{
                return response()->json(array('success' => false,'reason'=> 'request', 'project_name' => $project->name));
            }            
            
        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
    }

    public function searchProjects(Request $request){
        if (!Auth::check()) return redirect('/login');

        try {
            $projects = Auth::user()->searchUserProject($request->search);
            $html = view('partials.user_projects', ['projects' => $projects, 'user' => Auth::user()])->render();
            return response()->json(array('success' => true,'html' => $html));

        }catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
    }

    public function searchProjectsRole(Request $request){
        if (!Auth::check()) return redirect('/login');

        try {
            $projects = Auth::user()->searchUserProjectRole($request->role);
            $html = view('partials.user_projects', ['projects' => $projects, 'user' => Auth::user()])->render();
            return response()->json(array('success' => true,'html' => $html));

        }catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
    }

    public function leave(Request $request){
        if (!Auth::check()) return redirect('/login');
        try {

          $project = Project::find($request->input('project_id'));
    
          DB::table('project_members')->where([['project_id','=',$request->input('project_id')],['user_id','=',Auth::user()->id]])->delete();

          $projects = Auth::user()->userProjects();

          $html = view('partials.user_projects', ['projects' => $projects, 'user' => Auth::user()])->render();
          return response()->json(array('success' => true,'html' => $html, 'project_name' => $project->name));
          
        } catch(\Illuminate\Database\QueryException $qe) {
          // Catch the specific exception and handle it 
          //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
          // Handle unexpected errors
        }
      }
}

?>