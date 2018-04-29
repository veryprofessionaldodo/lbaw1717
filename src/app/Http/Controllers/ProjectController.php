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

        $viewHTML = view('partials.sprints_view', ['project' => $project, 'sprints'=>$sprints, 'role' => $role])->render();
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

        $viewHTML = view('partials.tasks_view', ['project' => $project, 'tasks'=>$tasks, 'role' => $role])->render();
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


    /* THREADS */
    public function threadsView($id){
      if(Auth::check()){
       $project = Project::find($id);
       $threads = Project::find($id)->threads()->with('user')->paginate(5);
       $notifications = Auth::user()->userNotifications();

        return view('pages/forum',['project' => $project,'threads' => $threads, 'notifications' => $notifications]);
      }
    }

    public function threadPageView($id,$thread_id){
      if(Auth::check()){
        $project = Project::find($id);
        $thread = Thread::find($thread_id);
        $comments = Thread::find($thread_id)->comments()->with('user')->get();
        $notifications = Auth::user()->userNotifications();
        $role = Auth::user()->projects()->find($project->id)->pivot->iscoordinator;
          if($role == false)
            $role = 'tm';
          else
            $role = 'co';
 
        return view('pages/thread_page',['project' => $project,'thread' => $thread, 'notifications' => $notifications, 'comments' => $comments, 'role' => $role]);
       }
    }

    public function threadsCreateForm($id){
      if(Auth::check()){
        $project = Project::find($id);
        $notifications = Auth::user()->userNotifications();
       
 
         return view('pages/new_thread_page',['project' => $project, 'notifications' => $notifications]);
       }
    }

    public function threadEditForm($id, $thread_id){
      if(Auth::check()){
        $project = Project::find($id);
        $notifications = Auth::user()->userNotifications();
        $thread = Thread::find($thread_id);
 
         return view('pages/edit_thread_page',['project' => $project, 'notifications' => $notifications, 'thread' => $thread]);
       }
    }

    public function searchProject(Request $request) {
      $notifications = Auth::user()->userNotifications();

      $projects = Project::search($request->input('search'))->with('user')->take(10)->get();

      return view('pages.result_search', ['projects' => $projects, 'notifications' => $notifications]);
    }

    public function threadsCreateAction(Request $request) {
      if (!Auth::check()) return redirect('/login');

      $user = Auth::user()->id;

      $thread = new Thread();
      //TODO authorize

      $thread->name = $request->input('name');
      $thread->description = $request->input('description');
      $thread->project_id = $request->input('project_id');
      $thread->user_creator_id = $user;

      $thread->save();

      $viewHTML = $this->threadsView($request->project_id)->render();
      return response()->json(array('success' => true, 'html' => $viewHTML)); 
    }

    public function threadEditAction(Request $request, $project_id, $thread_id) {
      if (!Auth::check()) return redirect('/login');

      $user = Auth::user()->id;

      $thread = Thread::find($thread_id);
      //TODO authorize

      $thread->name = $request->input('name');
      $thread->description = $request->input('description');
      $thread->project_id = $request->input('project_id');
      $thread->user_creator_id = $user;

      $thread->save();

      $viewHTML = $this->threadPageView($request->project_id, $thread_id)->render();
      return response()->json(array('success' => true, 'html' => $viewHTML)); 
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

      $categories = $request->input('categories');
      $cat_array = explode(',',$categories);

      foreach($cat_array as $cat) {
        Category::find($cat)->projects()->attach($project->id);
      }
      
      return $project;
    }

    /* COMMENT */

    public function storeComment(Request $request,$id, $thread_id){
      
      $thread = Thread::find($thread_id);
      /*$user = User::find($user_id);*/

      $comment = new Comment();
      $comment->content = $request->content;
    // $comment->thread_id = $thread_id;
      $comment->user_id = Auth::id();
      //$comment->associate($user);

      $thread->comments()->save($comment); 

      return back();
    }

    public function newTaskComment(Request $request, $project_id, $task_id) {

      $task = Task::find($task_id);

      $comment = new Comment();
      $comment->content = $request->content;
      $comment->user_id = Auth::id();

      $task->comments()->save($comment);

      return redirect()->route('project', ['project_id' => $project_id]);
    }

    public function projectStatisticsView($project_id){
      $notifications = Auth::user()->userNotifications();
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
      
      $monthlySprints = $project->monthlySprints()[0];

      return view('pages/statistics', ['notifications' => $notifications, 'project' => $project, 
      'tasksCompleted' => $tasksCompleted, 'sprintsCompleted' => $sprintsCompleted, 'topContributor1' => $topContributor1, 
      'topContributor2' => $topContributor2, 'topContributor3' => $topContributor3,
      'monthlySprints' => $monthlySprints]);
    }

    /*
    Deletes comment of task or thread
    */
    public function deleteComment(Request $request){

      $comment = Comment::find($request->input('comment_id'));
      $comment->delete();
    }

}
