<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\User;
use App\Report;
use App\Comment;


class AdminController extends Controller {

	public function showAdminPage(string $username){
		if (!Auth::check()) return redirect('/login');
        try {

            $userReports = Report::where('user_reported_id','!=',null)->with('users')->with('users_reported')->paginate(5);
            $notifications = Auth::user()->userNotifications();
            return view('pages/admin_page',['reports' => $userReports, 'type' => 'user', 'notifications' => $notifications]);

        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
          } catch (\Exception $e) {
              // Handle unexpected errors
          }

	}

    public function userReportsView(string $username) {
        if (!Auth::check()) return redirect('/login');

        
        try {
            $userReports = Report::where('user_reported_id','!=',null)->with('users')->with('users_reported')->paginate(5);
    
            $viewHTML = view('partials.reports_admin', ['reports'=>$userReports, 'type' => 'user'])->render();
            return response()->json(array('success' => true, 'html' => $viewHTML));

        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
    }

    public function commentReportsView(string $username) {
        if (!Auth::check()) return redirect('/login');
        
        try {
            $commentReports = Report::where('comment_reported_id','!=',null)->with('users')->with('comments_reported')
                                ->with('comments_reported.user')->paginate(5);
    
            $viewHTML = view('partials.reports_admin', ['reports'=>$commentReports, 'type' => 'comment'])->render();
            return response()->json(array('success' => true, 'html' => $viewHTML));
        
        } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }
    }

    public function dismissReport(Request $request){
        try {
            $report = Report::find($request->input('report_id'));
      
            $report->delete();
    
          } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
          } catch (\Exception $e) {
              // Handle unexpected errors
          }
    }

    public function disableUser(Request $request){
        try {

            $report = Report::find($request->input('report_id'));
            $user = User::find($report->user_reported_id);

            $user->disable = true;
            $report->delete();

          } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
          } catch (\Exception $e) {
              // Handle unexpected errors
          }
    }

    public function deleteComment(Request $request){
        try {

            $report = Report::find($request->input('report_id'));
            $comment = Comment::find($report->comment_reported_id);

            $comment->delete();
            $report->delete();

          } catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
          } catch (\Exception $e) {
              // Handle unexpected errors
          }
    }

}
