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
        $type = 'COMMENT';

        return view('pages/report_page',['user_reported' => $user_reported, 'notifications' => $notifications, 'type' => $type]);
    }

    /*
    Creates new user report
    */
    public function createUserReport(Request $request){
        if (!Auth::check()) return redirect('/login');

        $user = Auth::user()->id;
        $username_reported = $request->input('user_reported');
        $user_reported = User::where('username',$username_reported)->get();
  
        $report = new Report();
        //TODO authorize
  
        $report->summary = $request->input('summary');
        $report->user_reported_id = $user_reported[0]->id;
        $report->type = $request->input('type');
        $report->user_id = $user;
        $report->comment_reported_id = NULL;
  
        $report->save();

        if($report->type == 'userReported'){
            $user_controller = new UserController();
            $viewHTML = $user_controller->showProfile($username_reported)->render();
        }else{
            //TODO COMMENT REPORT
        }


        return response()->json(array('success' => true, 'html' => $viewHTML)); 

    }

    public function createCommentReport(){
        
    }
}
