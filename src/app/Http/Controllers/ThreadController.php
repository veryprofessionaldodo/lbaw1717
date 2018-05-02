<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Project;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ThreadController extends Controller {
    
    public function show(int $project_id, int $thread_id) {
        if(Auth::check()){
            try {
                
                $project = Project::find($project_id);
                $thread = Thread::find($thread_id);
                $comments = Thread::find($thread_id)->comments()->with('user')->get();
                
                $role = Auth::user()->isCoordinator($project_id);
                
                if($role == false)
                $role = 'tm';
                else
                $role = 'co';
                
                return view('pages/thread_page',['project' => $project,'thread' => $thread, 'comments' => $comments, 'role' => $role]);
                
            } catch(\Illuminate\Database\QueryException $qe) {
                // Catch the specific exception and handle it 
                //(returning the view with the parsed errors, p.e)
            } catch (\Exception $e) {
                // Handle unexpected errors
            }
        }
    }
    
    public function list(int $project_id) {
        if(Auth::check()){
            try {
                
                $project = Project::find($project_id);
                $threads = Thread::where('project_id','=',$project_id)->with('user')->paginate(5);
                
                return view('pages/forum',['project' => $project,'threads' => $threads]);
                
            } catch(\Illuminate\Database\QueryException $qe) {
                // Catch the specific exception and handle it 
                //(returning the view with the parsed errors, p.e)
            } catch (\Exception $e) {
                // Handle unexpected errors
            }
        }
    }
    
    public function create(int $project_id) {
        if(Auth::check()){
            
            $project = Project::find($project_id);
            return view('pages/new_thread_page',['project' => $project]);
            
        }
    }
    
    public function store(Request $request, int $project_id) {
        if (!Auth::check()) return redirect('/login');
        
        try {
            $thread = new Thread();
            
            //TODO authorize
            
            $thread->name = $request->input('title');
            $thread->description = $request->input('description');
            $thread->project_id = $project_id;
            $thread->user_creator_id = Auth::user()->id;
            
            $thread->save();
            return redirect()->route('forum', ['id' => $project_id]);
            
        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
    }
    
    public function edit($id, $thread_id){
        
        if(Auth::check()){
            try {
                
                $project = Project::find($id);
                $thread = Thread::find($thread_id);
                
                return view('pages/edit_thread_page',['project' => $project, 'thread' => $thread]);
                
            } catch(\Illuminate\Database\QueryException $qe) {
                // Catch the specific exception and handle it 
                //(returning the view with the parsed errors, p.e)
            } catch (\Exception $e) {
                // Handle unexpected errors
            }
        }
    }
    
    public function update(Request $request, $project_id, $thread_id) {
        if (!Auth::check()) return redirect('/login');
        
        try {
            
            $thread = Thread::find($thread_id);
            //TODO authorize
            
            $thread->name = $request->input('title');
            $thread->description = $request->input('description');
            $thread->project_id = $project_id;
            $thread->user_creator_id = Auth::user()->id;
            
            $thread->save();
            
            return redirect()->route('thread', ['id' => $project_id, 'thread_id' => $thread_id]);
            
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
    * @param  Request  $request
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request)
    {
        try {
            $thread = Thread::find($request->input('thread_id'));
            $project_id = $thread->project()->first()->id;
            
            $thread->delete();
            
            return response()->json(array('success' => true, 'url' => '/projects/'.$project_id.'/threads'));
            /*$viewHTML = $this->list($project_id)->render();
            return response()->json(array('success' => true, 'html' => $viewHTML));*/
            
        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
    }
}

?>