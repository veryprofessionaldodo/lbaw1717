<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Task;
use App\Task_state_record;

class TaskController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        //
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        //
    }
    
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        //
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        //
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
                
                $task_state_record = Task::find($task_id)
                ->task_state_records()
                ->where('state', 'Completed')
                ->first();
                
                $task_state_record->delete();
                
                $assigned_user = Task::find($task_id)
                ->task_state_records()
                ->where('state', 'Assigned')
                ->with('user')
                ->latest('date')->first();

                $claim_route = route('assign_self', ['project_id' => $id, 'task_id' => $task_id]);
                
                if($assigned_user != NULL){
                    $username = $assigned_user->user->username;
                    $image = "";
                    
                    if($assigned_user->user->image != NULL){
                        $image = asset('storage/'.$assigned_user->user->image);
                    }
                    else{
                        $image = asset('storage/1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg');
                    }
                    
                    $assigned = false;
                    if($username == Auth::user()->username){
                        $assigned = true;
                        //$claim_route = ...not assign
                    }

                    return response()->json(
                        array('success' => true, 'state' => $request->state, 
                        'task_id' => $task_id, 'coordinator' => Auth::user()->isCoordinator($id), 
                        'user_username' => $username, 'image' => $image, 'assigned' => $assigned,
                        'claim_url' => $claim_route)
                    );
                }
                else {
                    return response()->json(array('success' => true, 'state' => $request->state, 
                    'task_id' => $task_id, 'coordinator' => Auth::user()->isCoordinator($id),
                    'claim_url' => $claim_route));
                }
                
            }
            
        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
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
            $task_state_record = new Task_state_record();
            
            // TODO Authorize
            
            $task_state_record->state = "Assigned";
            $task_state_record->user_completed_id = Auth::user()->id;
            $task_state_record->task_id = $task_id;
            
            $task_state_record->save();
            
            $image = "";
            if(Auth::image() != NULL)
                $image = asset('storage/'.Auth::image());
            else
                $image = asset('storage/1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg');

            echo dd($image);
            return response()->json(array('success' => true, 'image' => $image,
                     'user_username' => Auth::username(), 'task_id' => $task_id));


        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        //
    }
}
