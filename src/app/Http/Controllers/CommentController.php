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
    public function store(Request $request, $id, $thread_id)
    {
        if (!Auth::check()) return redirect('/login');

        try {
            $thread = Thread::find($thread_id);
            
            $comment = new Comment();
            $comment->content = $request->content;
            $comment->user_id = Auth::id();
            
            $thread->comments()->save($comment); 
            

            $project = Project::find($id);
            $thread = Thread::find($thread_id);
            $role = Auth::user()->isCoordinator($id);

            if($role == false)
                $role = 'tm';
            else
                $role = 'co';

            $commentView = view('partials.comment', ['project' => $project, 'comment' => $comment, 'thread' => $thread, 'role' => $role])->render();
            return response()->json(array('success' => true, 'comment' => $commentView));
            
        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
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
            $task = Task::find($task_id);
            
            $comment = new Comment();
            $comment->content = $request->content;
            $comment->user_id = Auth::id();
            
            $task->comments()->save($comment);
            $comment->save();
            

            $project = Project::find($id);
            $task = Task::find($task_id);
            $role = Auth::user()->isCoordinator($id);
            if($role == false)
                $role = 'tm';
            else
                $role = 'co';


            $commentView = view('partials.comment', ['project' => $project, 'comment' => $comment, 'task' => $task, 'role' => $role])->render();
            return response()->json(array('success' => true, 'comment' => $commentView, 'task_id' => $task_id));
            
        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
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
    public function edit(Request $request, $id, $thread_id, $comment_id)
    {
        if (!Auth::check()) return redirect('/login');
        
        try {
        
            $comment = Comment::find($comment_id);

            $comment->content = $request->content;

            $comment->save();

            return back();

        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
 
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        //
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

        //authorize - check if creator, admin or coordinator

        try {
            $comment = Comment::find($request->input('comment_id'));
            $comment->delete();

            return response()->json(array('success' => true, 'comment_id' => $request->input('comment_id')));
            
        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
    }
}
