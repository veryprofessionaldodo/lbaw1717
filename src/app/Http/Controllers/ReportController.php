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

        $user_reported = User::where('username',$username)->get();
        $notifications = Auth::user()->userNotifications();
        $type = 'USER';

        return view('pages/report_page',['user_reported' => $user_reported[0], 'notifications' => $notifications, 'type' => $type]);
    }

    public function commentReportForm($comment_id){

        $notifications = Auth::user()->userNotifications();
        $comment = Comment::find($comment_id);
        $type = 'COMMENT';
        $project_id = Comment::find($comment_id)->thread()->first()->project()->first()->id;

        return view('pages/report_page',['comment' => $comment, 'notifications' => $notifications,
                     'type' => $type, 'project_id' => $project_id]);
    }

    /*
    Creates new user report
    */
    public function createReport(Request $request){
        if (!Auth::check()) return redirect('/login');

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
            $thread_id = Comment::find($report->comment_reported_id)->thread()->first()->id;
            $project_id = Comment::find($report->comment_reported_id)->thread()->first()->project()->first()->id;

            $viewHTML = $project_controller->threadPageView($project_id,$thread_id)->render();
        }


        return response()->json(array('success' => true, 'html' => $viewHTML)); 

    }
}
