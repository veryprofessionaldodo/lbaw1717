<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Task;
use App\Thread;
use App\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request, $id, $thread_id)
    {
        if (!Auth::check()) return redirect('/login');

        try {
            $project = Project::find($id);

            if(Auth::user()->isProjectMember($project)){
                $thread = Thread::find($thread_id);
                
                $comment = new Comment();
                $comment->content = $request->content;
                $comment->user_id = Auth::id();
                
                $thread->comments()->save($comment); 
                
    
                $thread = Thread::find($thread_id);
                $role = Auth::user()->isCoordinator($id);
    
                if($role == false)
                    $role = 'tm';
                else
                    $role = 'co';
    
                $commentView = view('partials.comment', ['project' => $project, 'comment' => $comment, 'thread' => $thread, 'role' => $role])->render();
                return response()->json(array('success' => true, 'comment' => $commentView));
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
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function storeCommentTask(Request $request, $id, $task_id)
    {
        if (!Auth::check()) return redirect('/login');

        try {
            $project = Project::find($id);
            if(Auth::user()->isProjectMember($project)){

                $task = Task::find($task_id);
                
                $comment = new Comment();
                $comment->content = $request->content;
                $comment->user_id = Auth::user()->id;
                
                $task->comments()->save($comment);
                $comment->save();
                

                $task = Task::find($task_id);
                $role = Auth::user()->isCoordinator($id);
                if($role == false)
                    $role = 'tm';
                else
                    $role = 'co';


                $commentView = view('partials.comment', ['project' => $project, 'comment' => $comment, 'task' => $task, 'role' => $role])->render();
                return response()->json(array('success' => true, 'comment' => $commentView, 'task_id' => $task_id));
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
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit(Request $request, $id, $thread_id, $comment_id)
    {
        if (!Auth::check()) return redirect('/login');
        
        try {
        
            $comment = Comment::find($comment_id);

            if(Auth::user()->id === $comment->user_id){
                $comment->content = $request->content;
    
                $comment->save();

                return redirect()->route('thread', ['id' => $id, 'thread_id' => $thread_id]);
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

    public function editAjax(Request $request, $id, $task_id, $comment_id)
    {
        if (!Auth::check()) return redirect('/login');
        
        try {
        
            $comment = Comment::find($comment_id);

            if(Auth::user()->id === $comment->user_id){
                $comment->content = $request->content;
    
                $comment->save();
    
                $project = Project::find($id);
                $task = Task::find($task_id);
                $role = Auth::user()->isCoordinator($id);
                
                if($role == false)
                    $role = 'tm';
                else
                    $role = 'co';
    
                $commentView = view('partials.comment', ['project' => $project, 'comment' => $comment, 'task' => $task, 'role' => $role])->render();
                return response()->json(array('success' => true, 'comment' => $commentView, 'task_id' => $task_id, 'comment_id' => $comment_id));
            }
            else{
                return response()->json(array('success' => false));
            }
        } catch(\Illuminate\Database\QueryException $qe) {
            return response()->json(array('success' => false));
        } catch (\Exception $e) {
            return response()->json(array('success' => false));
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
        if (!Auth::check()) return redirect('/login');

        try {
            $comment = Comment::find($request->input('comment_id'));
            if(Auth::user()->id === $comment->user_id || Auth::user()->isCoordinator($request->project_id) || Auth::user()->isAdmin()){
                $comment->delete();

                return response()->json(array('success' => true, 'comment_id' => $request->input('comment_id')));
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
}
