<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Project;
use App\Thread;
use App\Category;
use App\Comment;
use App\Task;
use App\User;
use App\Invite;


class ProjectController extends Controller
{
  public function show($id)
  {
    if(Auth::check()){
      
      try {
        $project = Project::find($id);
        $role = null;
        $members = $project->user()->get();
        
        if(Auth::user()->isProjectMember($project)){
          //if user is member
          $role = Auth::user()->isCoordinator($project->id);
          
          if($role == false)
          $role = 'tm';
          else
          $role = 'co';
          
        }
        else if($project->ispublic){
          
          $role = 'guest';
          
        }
        else
        $this->authorize('not_authorized', $project);
        
        
        $sprints = $project->SprintsTasksComments();
        
        return view('pages/project_page', ['project' => $project, 'sprints' => $sprints, 'role' => $role, 'members'=>$members]);
        
      } catch(\Illuminate\Database\QueryException $qe) {
        // Catch the specific exception and handle it 
        //(returning the view with the parsed errors, p.e)
      } catch (\Exception $e) {
        // Handle unexpected errors
      }
    }
    else {
      // TODO: do the visitor view 
    }
  }
  
  public function sprintsView($id)
  {
    if(Auth::check()){
      
      try {
        $project = Project::find($id);
        $role = null;
        if(Auth::user()->isProjectMember($project)){
          
          //if user is member
          $role = Auth::user()->isCoordinator($project->id);
          
          if($role == false)
          $role = 'tm';
          else
          $role = 'co';
          
        }
        else if($project->ispublic){
          
          $role = 'guest';
          
        }
        else
        $this->authorize('not_authorized', $project);
        
        $sprints = $project->SprintsTasksComments();
        
        //TODO: get user assigned to task
        
        $viewHTML = view('partials.sprints_view', ['project' => $project, 'sprints'=>$sprints, 'role' => $role])->render();
        return response()->json(array('success' => true, 'html' => $viewHTML));
        
      } catch(\Illuminate\Database\QueryException $qe) {
        // Catch the specific exception and handle it 
        //(returning the view with the parsed errors, p.e)
      } catch (\Exception $e) {
        // Handle unexpected errors
      }
      
    }
    else {
      // TODO: do the visitor view 
    }
  }
  
  
  public function taskView($id) {
    
    if(Auth::check()){
      try {
        $project = Project::find($id);
        
        $role = null;
        if(Auth::user()->isProjectMember($project)){
          //if user is member
          $role = Auth::user()->isCoordinator($project->id);
          
          if($role == false)
          $role = 'tm';
          else
          $role = 'co';
          
        }
        else if($project->ispublic){
          
          $role = 'guest';
          
        }
        else
          $this->authorize('not_authorized', $project);
        
        $tasks = $project->tasksComments();
        
        $viewHTML = view('partials.tasks_view', ['project' => $project, 'tasks'=>$tasks, 'role' => $role])->render();
        return response()->json(array('success' => true, 'html' => $viewHTML));
        
      } catch(\Illuminate\Database\QueryException $qe) {
        // Catch the specific exception and handle it 
        //(returning the view with the parsed errors, p.e)
      } catch (\Exception $e) {
        // Handle unexpected errors
      }
    }
    else
    return redirect('/login');
  }
  
  public function projectMembersView($id) {
    
    if(Auth::check()) {
      try {
        
        $project = Project::find($id);
        $members = $project->user()->get();

        $viewHTML = view('partials.project_members', ['members' => $members, 'project' => $project])->render();
        return response()->json(array('success' => true, 'html' => $viewHTML));
        
      } catch(\Illuminate\Database\QueryException $qe) {
        // Catch the specific exception and handle it 
        //(returning the view with the parsed errors, p.e)
      } catch (\Exception $e) {
        // Handle unexpected errors
      }
    }
    else 
    return redirect('/login');
  }
  
  public function searchProject(Request $request) {

    try {  
      // TODO: verify this!
      $projects = Project::search($request->input('search'))->with('user')->where('ispublic','=',true)->paginate(5);
      return view('pages.result_search', ['projects' => $projects]);
      
    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it 
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }
  }

  public function projectMembersSearch(Request $request){
    if(!Auth::check()) redirect('/login');

    try {

      $members = Project::find($request->project_id)->searchMember($request->search);
      $html = view('partials.members_view', ['members' => $members])->render();
      return response()->json(array('success' => true, 'html' => $html));

    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it 
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }
  }

  public function projectMembersSettingsSearch(Request $request){
    if(!Auth::check()) redirect('/login');

    try {
      $project = Project::find($request->project_id);
      $members = $project->searchMember($request->search);

      
      $html = view('partials.settings_members_view', ['project' => $project, 'members' => $members])->render();
      return response()->json(array('success' => true, 'html' => $html));

    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it 
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }
  }
  
  /**
  * Creates a new project.
  *
  * @return Project The project created.
  */
  public function create(Request $request)
  {
    $project = new Project();
    
    $this->authorize('create', $project);
    
    $project->name = $request->input('name');
    $project->description = $request->input('description');
    if($request->input('public') == 'on')
    $project->ispublic = TRUE;
    else
    $project->ispublic = FALSE;
    
    try {
      $project->save();
      
      $project->user()->attach($request->input('user_id'), ['iscoordinator' => true]);
      
      $categories = $request->input('categories');
      $cat_array = explode(',',$categories);
      
      foreach($cat_array as $cat) {
        Category::find($cat)->projects()->attach($project->id);
      }
      
      return back();
      
    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it 
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }
  }
  
  
  public function projectStatisticsView($project_id){
    try {
      $project = Project::find($project_id);
      $tasksCompleted = $project->tasksCompleted()[0];
      $sprintsCompleted = $project->sprintsCompleted()[0];
      
      
      if(count($project->topContributors())==0){
        $topContributor1 = $project->zeroContributors()[0];
        $topContributor2 = $project->zeroContributors()[1];
        $topContributor3 = $project->zeroContributors()[2];
      }
      else if(count($project->topContributors())==1){
        $topContributor1 = $project->topContributors()[0];
        $topContributor2 = $project->zeroContributors()[1];
        $topContributor3 = $project->zeroContributors()[2];
      }
      else if(count($project->topContributors())==2){
        $topContributor1 = $project->topContributors()[0];
        $topContributor2 = $project->topContributors()[1];
        $topContributor3 = $project->zeroContributors()[2];
      }
      else{
        $topContributor1 = $project->topContributors()[0];
        $topContributor2 = $project->topContributors()[1];
        $topContributor3 = $project->topContributors()[2];
      }
      
     // $monthlySprints = $project->monthlySprints()[0];
      
      return view('pages/statistics', ['project' => $project, 
      'tasksCompleted' => $tasksCompleted, 'sprintsCompleted' => $sprintsCompleted, 'topContributor1' => $topContributor1, 
      'topContributor2' => $topContributor2, 'topContributor3' => $topContributor3,
      /*'monthlySprints' => $monthlySprints*/]);
      
    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it 
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }
  }

  /*
  settings page
  */
  public function projectSettings($project_id){
    if (!Auth::check()) return redirect('/login');
    try {
      $project = Project::find($project_id);
      $requests = $project->invites()->get();
  
      return view('pages/project_settings', ['project' => $project, 'requests' => $requests])->render();
      
    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it 
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }
  }

  /*
  asks for the requests (settings)
  */
  public function projectSettingsRequestsView($project_id){
    if (!Auth::check()) return redirect('/login');
    try {
      $project = Project::find($project_id);
      $requests = $project->invites()->get();
  
      $viewHTML =  view('partials/project_settings_requests', ['project' => $project, 'requests' => $requests])->render();
      return response()->json(array('success' => true, 'html' => $viewHTML)); 
      
    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it 
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }
  }
  
  /*
  asks for the members (settings) 
  */
  public function projectSettingsMembersView($project_id){
    if (!Auth::check()) return redirect('/login');
  
    try {
      $project = Project::find($project_id);
      $members = $project->user()->get();

      $viewHTML = view('partials/project_settings_members', ['project' => $project, 'members' => $members])->render();
      return response()->json(array('success' => true, 'html' => $viewHTML));
      
    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it 
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }
  }

  public function projectSettingsRequestsReject(Request $request){
    if (!Auth::check()) return redirect('/login');
    try {

      $request = Project::find($request->input('project_id'))->invites()->where('id','=',$request->input('request_id'))->first();
      $request_id = $request->id;
      
      $request->delete();

      return response()->json(array('success' => true, 'request_id' => $request_id));
      
    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it 
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }
  }

  public function projectSettingsPromote(Request $request){
    if (!Auth::check()) return redirect('/login');
    try {

      $user = User::where('username','=',$request->input('username'))->first();
      
      DB::table('project_members')->where([['project_id','=',$request->input('project_id')],['user_id','=',$user->id]])->update(
        ['iscoordinator' => true]
      );

      //$users= DB::table('project_members')->where([['project_id','=',$request->input('project_id')],['user_id','=',$user->id]])->get();
      //return $users;
      return response()->json(array('success' => true, 'member_username' => $user->username));
      
    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }
  }

  /*
  Removes user from project
   */
  public function projectSettingsMembersRemove(Request $request){
    if (!Auth::check()) return redirect('/login');
    try {

      $user = User::where('username','=',$request->input('username'))->first();

      DB::table('project_members')->where([['project_id','=',$request->input('project_id')],['user_id','=',$user->id]])->delete();

      return response()->json(array('success' => true, 'member_username' => $user->username));
      
    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it 
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }
  }

  public function projectSettingsRequestsAccept(Request $request){
    if (!Auth::check()) return redirect('/login');
    try {

      $request = Project::find($request->input('project_id'))->invites()->where('id','=',$request->input('request_id'))->first();
      $request_id = $request->id;

      DB::table('project_members')->insert(
        ['user_id' => $request->user_invited_id , 'project_id' =>$request->project_id , 'iscoordinator' => FALSE]
      );

      $request->delete();

      return response()->json(array('success' => true, 'request_id' => $request_id));
      
    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it 
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }
  }

  public function editForm($project_id){
    if (!Auth::check()) return redirect('/login');
    try {

      $project = Project::find($project_id);
      $categories = Category::all();
      $viewHTML = view('pages/edit_project_page',['project' => $project, 'categories' => $categories])->render();
      return response()->json(array('success' => true, 'html' => $viewHTML));
      
    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it 
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }

  }

  public function edit(Request $request){
    if (!Auth::check()) return redirect('/login');    
    try {
      $project = Project::find($request->input('project_id'));
      $old_categories = $project->categories()->get();
      
      $project->name = $request->input('name');
      $project->description = $request->input('description');

      if($request->input('public') == 'on')
        $project->ispublic = TRUE;
      else
        $project->ispublic = FALSE;

      $project->save(); //saves edited information

      //removes old categories associated with this project
      foreach($old_categories as $old_cat){
        Category::find($old_cat->id)->projects()->detach($project->id);
      }
      
      $new_categories = $request->input('categories');
      $cat_array = explode(',',$new_categories);

      if(!empty($new_categories)){
        foreach($cat_array as $cat) {
          Category::find($cat)->projects()->attach($project->id); //sets the new categories of the project
        }
      }
      
      return back();
      
    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it 
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }
  }

  public function destroy(Request $request){
    if (!Auth::check()) return redirect('/login');
    try {
      $project = Project::find($request->input('project_id'));

      $project->delete();

      return response()->json(array('success' => true, 'url' => '/api/users/'. Auth::user()->username));
      
    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it 
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }
  }

  public function inviteMember(Request $request){
    if (!Auth::check()) return redirect('/login');
    try {

      $user = User::where('username','=',$request->input('username'))->first();

      if($user == null) //if input invalid
        return response()->json(array('success' => false,'reason' => 'user'));

      $invite = Invite::where([['project_id','=',$request->input('project_id')],['user_invited_id','=',$user->id]])->first(); 

      if($invite != null) //if has alredy been sent and invite to the user
        return response()->json(array('success' => false, 'reason' => 'invite'));

      $invite = DB::table('project_members')->where([['project_id','=',$request->input('project_id')],['user_id','=',$user->id]])->first();

      if($invite != null)//if the user is already a member
        return response()->json(array('success' => false, 'reason' => 'project_member'));

      $invite = new Invite();

      $invite->user_invited_id = $user->id;
      $invite->project_id = $request->input('project_id');
      $invite->user_who_invited_id = Auth::user()->id;

      $invite->save();

      return response()->json(array('success' => true,'username', $user->username));
      
    } catch(\Illuminate\Database\QueryException $qe) {
      // Catch the specific exception and handle it 
      //(returning the view with the parsed errors, p.e)
    } catch (\Exception $e) {
      // Handle unexpected errors
    }
  }
}
