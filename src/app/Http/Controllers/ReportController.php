<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Project;
use App\Thread;
use App\Comment;
use App\Task;
use App\User;
use App\Report;

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\ProjectController;

class ReportController extends Controller
{

    /* gets the report form page*/
    public function userReportForm($username){
        if(!Auth::check()) return redirect('/login');
        try {
            $user_reported = User::where('username',$username)->first();
            $type = 'USER';
            
            return view('pages/report_page',['user_reported' => $user_reported, 'type' => $type]);
        } catch(\Illuminate\Database\QueryException $qe) {
            return redirect()->route('error');
        } catch (\Exception $e) {
            return redirect()->route('error');
        }

    }

    public function commentReportForm($comment_id){
        if(!Auth::check()) return redirect('/login');
        try {

            $comment = Comment::find($comment_id);
            $type = 'COMMENT';
    
            if($comment->thread_id == NULL){
                $project_id = Comment::find($comment_id)->task()->first()->project()->first()->id;
            }else{
                $project_id = Comment::find($comment_id)->thread()->first()->project()->first()->id;
            }
            return view('pages/report_page',['comment' => $comment,
                         'type' => $type, 'project_id' => $project_id]);


        } catch(\Illuminate\Database\QueryException $qe) {
            return redirect()->route('error');
        } catch (\Exception $e) {
            return redirect()->route('error');
        }
        
    }

    /*
    Creates new user report
    */
    public function createReport(Request $request){
        if (!Auth::check()) return redirect('/login');

        try {
            $user = Auth::user()->id;
            $report = new Report();
            
            $report->summary = $request->input('summary');
            $report->user_id = $user;
            $report->type = $request->input('type');
    
            if($report->type == 'userReported'){
    
                $username_reported = $request->input('user_reported');
                $user_reported = User::where('username',$username_reported)->get();
                $report->user_reported_id = $user_reported[0]->id;
                $report->comment_reported_id = NULL;
    
            }elseif($report->type == 'commentReported'){
                $report->comment_reported_id = $request->input('comment_id');
                $report->user_reported_id = NULL;
            }    
      
            $report->save();

            if($report->type == 'userReported'){
    
                $user_controller = new UserController();
                $viewHTML = $user_controller->showProfile($username_reported)->render();
    
            }elseif($report->type == 'commentReported'){
    
                $project_controller = new ProjectController();
                $comment = Comment::find($report->comment_reported_id);
                
                if($comment->task_id == NULL){
                    $project_id = Comment::find($report->comment_reported_id)->thread()->first()->project()->first()->id;
                    $thread_id = Comment::find($report->comment_reported_id)->thread()->first()->id;
                    $viewHTML = $project_controller->threadPageView($project_id,$thread_id)->render();
                }else{
                    $project_id = Comment::find($report->comment_reported_id)->task()->first()->project()->first()->id;
                    $viewHTML = $project_controller->project($project_id)->render();
                }
            }
            return response()->json(array('success' => true, 'html' => $viewHTML));
            
            
        } catch(\Illuminate\Database\QueryException $qe) {
            return response()->json(array('success' => false));
        } catch (\Exception $e) {
            return response()->json(array('success' => false));
        }

    }
}
