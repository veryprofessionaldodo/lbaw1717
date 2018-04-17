<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Project;
use App\Thread;

use App\ProjectMember;

class ProjectController extends Controller
{
    public function project($id)
    {
      if(Auth::check()){

        $project = Project::find($id);
        $role = null;
        if(Auth::user()->projects()->get()->contains($project)){
          //if user is member
          $role = Auth::user()->projects()->find($project->id)->pivot->iscoordinator;
          if($role == false)
            $role = 'tm';
          else
            $role = 'co';
        }
        else if($project->ispublic){
          //if project is public
          $role = 'guest';
        }
        else
          $this->authorize('not_authorized', $project);

        $sprints = Project::find($id)->sprints()->with('tasks')->with('tasks.comments')->with('tasks.comments.user')->get();
        //TODO: get user assigned to task

        $notifications = Auth::user()->userNotifications();

        return view('pages/project_page', ['project' => $project, 'sprints' => $sprints, 'notifications' => $notifications, 'role' => $role]);
      }
      else {
        // TODO: do the visitor view 
      }
    }

    public function sprintsView($id)
    {
      if(Auth::check()){

        $project = Project::find($id);
        $role = null;
        if(Auth::user()->projects()->get()->contains($project)){
          //if user is member
          $role = Auth::user()->projects()->find($project->id)->pivot->iscoordinator;
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

        $sprints = Project::find($id)->sprints()->with('tasks')->with('tasks.comments')->with('tasks.comments.user')->get();
        //TODO: get user assigned to task

        $viewHTML = view('partials.sprints_view', ['sprints'=>$sprints, 'role' => $role])->render();
        return response()->json(array('success' => true, 'html' => $viewHTML));
        
      }
      else {
        // TODO: do the visitor view 
      }
    }


    public function taskView($id) {
      if(Auth::check()){

        $project = Project::find($id);
        $role = null;
        if(Auth::user()->projects()->get()->contains($project)){
          //if user is member
          $role = Auth::user()->projects()->find($project->id)->pivot->iscoordinator;
          if($role == false)
            $role = 'tm';
          else
            $role = 'co';
        }
        else if($project->ispublic){
          //if project is public
          $role = 'guest';
        }
        else
          $this->authorize('not_authorized', $project);

        $tasks = Project::find($id)->tasks()->where('task.sprint_id','=',null)->with('comments')->with('comments.user')->get();

        $viewHTML = view('partials.tasks_view', ['tasks'=>$tasks, 'role' => $role])->render();
        return response()->json(array('success' => true, 'html' => $viewHTML));
      }
      else
        return redirect('/login');
    }

    public function projectMembersView($id) {
      if(Auth::check()) {

        $members = Project::find($id)->user()->get();
        
        $viewHTML = view('partials.project_members', ['members' => $members])->render();
        return response()->json(array('success' => true, 'html' => $viewHTML));
      }
      else 
        return redirect('/login');
    }


    public function threadsView($id){
      if(Auth::check()){
       $project = Project::find($id);
       $threads = Project::find($id)->threads()->with('user')->get();
       $notifications = Auth::user()->userNotifications();

      /* foreach($threads as $thread){
        echo($thread->name);
       }*/
       
      /* FALTA O USER QUE O CRIOU e a cena das páginas*/

        return view('pages/forum',['project' => $project,'threads' => $threads, 'notifications' => $notifications]);
      }
    }

    public function threadPageView($id,$thread_id){
      if(Auth::check()){
        $project = Project::find($id);
        $thread = Thread::find($thread_id);
        $comments = Thread::find($thread_id)->comments()->with('user')->get();
        $notifications = Auth::user()->userNotifications();
 
        return view('pages/thread_page',['project' => $project,'thread' => $thread, 'notifications' => $notifications, 'comments' => $comments]);
       }
    }

    public function threadsCreateForm($id){
      if(Auth::check()){
        $project = Project::find($id);
        $notifications = Auth::user()->userNotifications();
 
         return $viewHTML = view('pages/new_thread_page',['project' => $project, 'notifications' => $notifications]);
       }
    }

    public function searchProject(Request $request) {
      $notifications = Auth::user()->userNotifications();

      $projects = Project::search($request->input('search'))->with('user')->take(10)->get();

      return view('pages.result_search', ['projects' => $projects, 'notifications' => $notifications]);
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
      $project->save();
      $project->user()->attach($request->input('user_id'), ['iscoordinator' => true]);

      /*$project_member = new ProjectMember();
      $project_member->user_id = $request->input('user_id');
      $project_member->project_id = $project->id;
      $project_member->iscoordinator = TRUE;
      $project_member->save();*/

      /*DB::insert('insert into project_members (user_id, project_id, iscoordinator) values (?,?,?)',
                  array($request->input('user_id'),
                        $project->id,
                        TRUE));*/

      return $project;
    }
}
