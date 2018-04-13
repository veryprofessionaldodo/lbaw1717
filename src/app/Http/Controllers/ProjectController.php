<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Project;

class ProjectController extends Controller
{
    /**
     * Shows the card for a given id.
     *
     * @param  int  $id
     * @return Response
     */
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
          //if project is public
          $role = 'guest';
        }
        else
          $this->authorize('not_authorized', $project);

        $sprints = Project::find($id)->sprints()->with('tasks')->with('tasks.comments')->with('tasks.comments.user')->get();
        //TODO: get user assigned to task

        /*foreach($sprints as $sprint){
          //echo $sprint->name . '::';
          foreach($sprint->tasks as $task){
            //echo $task->name;
            foreach($task->comments as $comment)
              echo $comment;
          }
          //echo '\n';
        }*/

        $notifications = Auth::user()->userNotifications();

        return view('pages/project_page', ['project' => $project, 'sprints' => $sprints, 'notifications' => $notifications, 'role' => $role]);
      }
      else {
        // TODO: do the visitor view 
      }
    }

    /**
     * Shows all cards.
     *
     * @return Response
     */
    public function list()
    {
      
    }

    /**
     * Creates a new card.
     *
     * @return Card The card created.
     */
    public function create(Request $request)
    {
      
    }

    public function delete(Request $request, $id)
    {
      
    }
}
