<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Project;
use App\Task;
use App\Task_state_record;

class TaskController extends Controller
{
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if (!Auth::check()) return redirect('/login');
        
        try {

            if(Auth::user()->isCoordinator($request->project_id)){
                $task = new Task();
                if($request->sprint_id != null){
                    $task->sprint_id = $request->sprint_id;
                }
                $task->project_id = $request->project_id;
                $task->name = $request->name;
                $task->effort = $request->effort;
    
                $task->save();
    
                $role = 'tm';
                if(Auth::user()->isCoordinator($request->project_id)){
                    $role = 'co';
                }
    
                $project = Project::find($request->project_id);
                
                $viewHTML = view('partials.task', ['project' => $project, 
                                'task' => $task, 'role' => $role])->render();
    
                return response()->json(array('success' => true, 'sprint_id' => $request->sprint_id,
                                                'html' => $viewHTML));

            }
            else {
                return response()->json(array('success' => false));
            }

        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it (max effort surpassed)
            return response()->json(array('success' => false, 'sprint_id' => $request->sprint_id));
        
        } catch (\Exception $e) {
            return response()->json(array('success' => false));
        }
    }
    
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id, $task_id)
    {
        if (!Auth::check()) return redirect('/login');
        
        try {
            
            $project = Project::find($id);
            if(Auth::user()->isProjectMember($project)){
                $task = Task::find($task_id);
                $comments = $task->comments()->get();
    
                $assigned_user = Task::find($task_id)->userAssigned();
                    
                $claim_route = route('assign_self', ['project_id' => $id, 'task_id' => $task_id]);
                
                $username = null;
                $image = null;
    
                if($assigned_user != NULL){
                    
                    $username = $assigned_user[0]->username;
                    
                    if($assigned_user[0]->image != NULL){
                        $image = asset('storage/'.$assigned_user[0]->image);
                    }
                    else{
                        $image = asset('storage/1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg');
                    }
                    
                    $assigned = false;
                    if($username == Auth::user()->username){
                        $assigned = true;
                        $claim_route = route('unassign_self', ['project_id' => $id, 'task_id' => $task_id]);
                    }
                
                }
    
                return view('pages/task_page', ['task' => $task, 'project' => $project, 
                'comments' => $comments,'coordinator' => Auth::user()->isCoordinator($id), 
                'user_username' => $username,'image' => $image, 'claim_url' => $claim_route]);

            }
            else {
                return redirect()->route('error');
            }

        } catch(\Illuminate\Database\QueryException $qe) {
            return redirect()->route('error');
        } catch (\Exception $e) {
            return redirect()->route('error');
        }
    }
    
    /**
    * Changes the task description
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit(Request $request, $id, $task_id)
    {
        if (!Auth::check()) return redirect('/login');
        
        try {
            
            if(Auth::user()->isCoordinator($id)){
                $task = Task::find($task_id);
    
                $task->description = $request->description;
    
                $task->save();
    
                return back();

            }
            else {
                return redirect()->route('error');
            }

        } catch(\Illuminate\Database\QueryException $qe) {
            return redirect()->route('error');
        } catch (\Exception $e) {
            return redirect()->route('error');
        }
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id, $task_id)
    {
        if (!Auth::check()) return redirect('/login');
        
        try {
            $project = Project::find($id);

            if(Auth::user()->isProjectMember($project)){
                if($request->state == "Completed"){
                    
                    $task_state_record = new Task_state_record();
                    
                    // TODO Authorize
                    
                    $task_state_record->state = $request->state;
                    $task_state_record->user_completed_id = Auth::id();
                    $task_state_record->task_id = $task_id;
                    
                    $task_state_record->save();
                    
                    return response()->json(array('success' => true, 'state' => $request->state, 
                    'task_id' => $task_id, 'coordinator' => Auth::user()->isCoordinator($id)));
                    
                } else if($request->state == "Uncompleted"){
                    
                    //deletes register of task completed
                    $task_state_record = Task::find($task_id)
                    ->task_state_records()
                    ->where('state', 'Completed')
                    ->first();
                    
                    $task_state_record->delete();
                    
                    // gets user assigned
                    $task = Task::find($task_id);
                    $assigned_user = $task->userAssigned();
                    $project = $task->project()->get()[0];
                    
                    //gets claim url
                    $claim_route = route('assign_self', ['project_id' => $id, 'task_id' => $task_id]);

                    // gets user assigned information
                    if($assigned_user != NULL){
                        
                        $username = $assigned_user[0]->username;
                        $image = "";
                        
                        if($assigned_user[0]->image != NULL){
                            $image = asset('storage/'.$assigned_user[0]->image);
                        }
                        else{
                            $image = asset('storage/1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg');
                        }
                        
                        $assigned = false;

                        if($username == Auth::user()->username){
                            $assigned = true;
                            $claim_route = route('unassign_self', ['project_id' => $id, 'task_id' => $task_id]);
                        }

                        //if coordinator, sends view of coordinator options
                        if(Auth::user()->isCoordinator($id)){

                            $html = view('partials.task_coordinator_options',['project' => $project, 'task' => $task, 'user_username' => $username])->render();
                            
                            return response()->json(
                                array('success' => true, 'state' => $request->state, 
                                'task_id' => $task_id, 'coordinator' => true, 
                                'user_username' => $username, 'image' => $image, 'assigned' => $assigned,
                                'coordinator_options' => $html)
                            );
                        }
                        else {

                            return response()->json(
                                array('success' => true, 'state' => $request->state, 
                                'task_id' => $task_id, 'coordinator' => false, 
                                'user_username' => $username, 'image' => $image, 'assigned' => $assigned,
                                'claim_url' => $claim_route)
                            );
                        }
                        
                    }
                    else {
                        if(Auth::user()->isCoordinator($id)){

                            $html = view('partials.task_coordinator_options',['project' => $project, 'task' => $task, 'user_username' => null])->render();
                            
                            return response()->json(
                                array('success' => true, 'state' => $request->state, 
                                'task_id' => $task_id, 'coordinator' => true, 
                                'coordinator_options' => $html)
                            );
                        }
                        else {

                            return response()->json(
                                array('success' => true, 'state' => $request->state, 
                                'task_id' => $task_id, 'coordinator' => false, 
                                'claim_url' => $claim_route)
                            );
                        }
                    }
                    
                }
            }
            else {
                return response()->json(array('success' => false));
            }
            
        } catch(\Illuminate\Database\QueryException $qe) {

            if($request->state === "Uncompleted"){
                return response()->json(array('success' => false,'error' => 'Error changing the state of the task to UNCOMPLETED'));
            }

            return response()->json(array('success' => false,'error' => 'Error changing the state of the task to COMPLETED '.$qe));
        
        } catch (\Exception $e) {
            return response()->json(array('success' => false));
        }
        
    }
    
    /**
    * Assign authenticated user to task
    *
    * @param  int  $id
    * @param  int  $task_id
    * @return \Illuminate\Http\Response
    */
    public function assignSelf(Request $request, $id, $task_id) {
        if (!Auth::check()) return redirect('/login');
        
        try {         
            $project = Project::find($id);
            if(Auth::user()->isProjectMember($project)){
                $task_state_record = new Task_state_record();
                
                // TODO Authorize
                
                $task_state_record->state = 'Assigned';
                $task_state_record->user_completed_id = Auth::user()->id;
                $task_state_record->task_id = $task_id;
                
                $task_state_record->save();
                
                $image = "";
                if(Auth::user()->image != NULL){
                    $image = asset('storage/'.Auth::user()->image);
                }
                else{
                    $image = asset('storage/1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg');
                }
                
                $unclaim_url = route('unassign_self', ['project_id' => $id, 'task_id' => $task_id]);
                
                return response()->json(array('success' => true, 'image' => $image,
                'user_username' => Auth::user()->username,'unclaim_url' => $unclaim_url));

            }
            else {
                return response()->json(array('success' => false));
            }
            
            
        } catch(\Illuminate\Database\QueryException $qe) {
            return response()->json(array('success' => false));
        } catch (\Exception $e) {
            return response()->json(array('success' => false));
        }
    }
    
    public function unassignSelf(Request $request, $id, $task_id) {
        if (!Auth::check()) return redirect('/login');
        
        try {          
            $project = Project::find($id);
            if(Auth::user()->isProjectMember($project)){
                $task_state_record = new Task_state_record();
                
                
                $task_state_record->state = 'Unassigned';
                $task_state_record->user_completed_id = Auth::user()->id;
                $task_state_record->task_id = $task_id;
                
                $task_state_record->save();
                
                $claim_url = route('assign_self', ['project_id' => $id, 'task_id' => $task_id]);
                return response()->json(array('success' => true,'claim_url' => $claim_url));

            }
            else {
                return response()->json(array('success' => false));
            }
            
            
        } catch(\Illuminate\Database\QueryException $qe) {
            return response()->json(array('success' => false));
        } catch (\Exception $e) {
            return response()->json(array('success' => false));
        }
    }

    /**
    * Assign other user to task
    *
    * @param  int  $id
    * @param  int  $task_id
    * @return \Illuminate\Http\Response
    */
    public function assign(Request $request, $id, $task_id) {
        if (!Auth::check()) return redirect('/login');
        
        try {         
            
            if(Auth::user()->isCoordinator($id)){
                $user = User::where('username',$request->username)->first();
                $project = Project::find($id);
    
                if($user === null) {
                    return response()->json(array('success' => false, 'error' => 'User isn\'t member of the project'));    
                }
                else if(!$user->isProjectMember($project)){
                    return response()->json(array('success' => false, 'error' => 'User isn\'t member of the project'));    
                }
    
                
                $task_state_record = new Task_state_record();
                $task_state_record->state = 'Assigned';
                $task_state_record->user_completed_id = $user->id;
                $task_state_record->task_id = $task_id;
                
                $task_state_record->save();
                
                $image = "";
                if($user->image != NULL){
                    $image = asset('storage/'.$user->image);
                }
                else{
                    $image = asset('storage/1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg');
                }
                
                $unclaim_url = route('unassign_other', ['project_id' => $id, 'task_id' => $task_id]);
                $claim_self_url = route('assign_self', ['project_id' => $id, 'task_id' => $task_id]);
                
                return response()->json(array('success' => true, 'image' => $image,
                'user_username' => $user->username,'unclaim_url' => $unclaim_url, 'claim_self_url' => $claim_self_url));
                
            }
            else {
                return response()->json(array('success' => false));
            }

        } catch(\Illuminate\Database\QueryException $qe) {
            return response()->json(array('success' => false));
        } catch (\Exception $e) {
            return response()->json(array('success' => false));
        }
    }

    public function unassign(Request $request, $id, $task_id) {
        if (!Auth::check()) return redirect('/login');
        
        try {
            $project = Project::find($id);
            if(Auth::user()->isProjectMember($project)){
                $task_state_record = new Task_state_record();
                
                
                $task_state_record->state = 'Unassigned';
                $task_state_record->user_completed_id = Auth::user()->id;
                $task_state_record->task_id = $task_id;
                
                $task_state_record->save();
                
                $claim_url = route('assign_self', ['project_id' => $id, 'task_id' => $task_id]);
                return response()->json(array('success' => true,'claim_url' => $claim_url));

            }          
            else {
                return response()->json(array('success' => false));
            }
            
        } catch(\Illuminate\Database\QueryException $qe) {
            return response()->json(array('success' => false,'error' => 'Error removing assigned user'));
        } catch (\Exception $e) {
            return response()->json(array('success' => false));
        }
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $project_id
    * @param  int  $task_id
    * @return \Illuminate\Http\Response
    */
    public function destroy($project_id, $task_id)
    {
        if (!Auth::check()) return redirect('/login');

        try {
            $task = Task::find($task_id);

            if(Auth::user()->isCoordinator($project_id) || Auth::user()->isAdmin()){
                $task->delete();

                return redirect()->route('project', ['project_id' => $project_id]);
            }
            else {
                return redirect()->route('error');
            }
            
        } catch(\Illuminate\Database\QueryException $qe) {
            return redirect()->route('error');
        } catch (\Exception $e) {
            return redirect()->route('error');
        }
    }
}
