<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Task;
use App\Thread;

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
        try {
            $thread = Thread::find($thread_id);
            
            $comment = new Comment();
            $comment->content = $request->content;
            $comment->user_id = Auth::id();
            
            $thread->comments()->save($comment); 
            
            return back();
            
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
        try {
            $task = Task::find($task_id);
            
            $comment = new Comment();
            $comment->content = $request->content;
            $comment->user_id = Auth::id();
            
            $task->comments()->save($comment);
            $comment->save();
            
            return redirect()->route('project', ['project_id' => $id]);
            
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
        try {
            $comment = Comment::find($request->input('comment_id'));
            $comment->delete();
            
        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
    }
}
