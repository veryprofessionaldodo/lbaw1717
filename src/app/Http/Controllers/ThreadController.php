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
                if(Auth::user()->isProjectMember($project)){
                    $thread = Thread::find($thread_id);
                    $comments = Thread::find($thread_id)->comments()->with('user')->get();
                    
                    $role = Auth::user()->isCoordinator($project_id);
                    
                    if($role == false)
                    $role = 'tm';
                    else
                    $role = 'co';
                    
                    return view('pages/thread_page',['project' => $project,'thread' => $thread, 'comments' => $comments, 'role' => $role]);

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
    
    public function list(int $project_id) {
        if(Auth::check()){
            try {
                
                $project = Project::find($project_id);
                if(Auth::user()->isProjectMember($project)){
                    $threads = Thread::where('project_id','=',$project_id)->with('user')->paginate(5);
                    
                    return view('pages/forum',['project' => $project,'threads' => $threads]);

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
    
    public function create(int $project_id) {
        if(Auth::check()){
            
            $project = Project::find($project_id);
            if(Auth::user()->isProjectMember($project)){
                return view('pages/new_thread_page',['project' => $project]);
            }
            else {
                return redirect()->route('error');
            }
        }
        else
            return redirect('/login');
    }
    
    public function store(Request $request, int $project_id) {
        if (!Auth::check()) return redirect('/login');
        
        try {

            $project = Project::find($project_id);
            if(Auth::user()->isProjectMember($project)){
                $thread = new Thread();
                
                $thread->name = $request->input('title');
                $thread->description = $request->input('description');
                $thread->project_id = $project_id;
                $thread->user_creator_id = Auth::user()->id;
                
                $thread->save();
                return redirect()->route('forum', ['id' => $project_id]);

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
    
    public function edit($id, $thread_id){
        
        if(Auth::check()){
            try {
                
                $project = Project::find($id);
                $thread = Thread::find($thread_id);

                if(Auth::user()->id === $thread->user_creator_id){
                    return view('pages/edit_thread_page',['project' => $project, 'thread' => $thread]);
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
    
    public function update(Request $request, $project_id, $thread_id) {
        if (!Auth::check()) return redirect('/login');
        
        try {
            
            $thread = Thread::find($thread_id);
            
            if(Auth::user()->id === $thread->user_creator_id){
                $thread->name = $request->input('title');
                $thread->description = $request->input('description');
                $thread->project_id = $project_id;
                $thread->user_creator_id = Auth::user()->id;
                
                $thread->save();
                
                return redirect()->route('thread', ['id' => $project_id, 'thread_id' => $thread_id]);

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
            
            if(Auth::user()->id === $thread->user_creator_id || Auth::user()->isCoordinator($project_id)
            || Auth::user()->isAdmin){
                $thread->delete();
                
                return response()->json(array('success' => true, 'url' => '/projects/'.$project_id.'/threads'));

            }
            
        } catch(\Illuminate\Database\QueryException $qe) {
            return response()->json(array('success' => false));
        } catch (\Exception $e) {
            return response()->json(array('success' => false));
        }
    }
}

?>