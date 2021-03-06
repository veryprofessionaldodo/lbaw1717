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
            if(Auth::user()->username === $username){
                $userReports = Report::where('user_reported_id','!=',null)->with('users')->with('users_reported')->paginate(5);
                $notifications = Auth::user()->userNotifications();
                return view('pages/admin_page',['reports' => $userReports, 'type' => 'user', 'notifications' => $notifications]);
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

    public function userReportsView(string $username) {
        if (!Auth::check()) return redirect('/login');

        
        try {
            if(Auth::user()->username === $username){
                $userReports = Report::where('user_reported_id','!=',null)->with('users')->with('users_reported')->paginate(5);
        
                $viewHTML = view('partials.reports_admin', ['reports'=>$userReports, 'type' => 'user'])->render();
                return response()->json(array('success' => true, 'html' => $viewHTML));
            }
            else{
                return redirect()->route('error');
            }

        } catch(\Illuminate\Database\QueryException $qe) {
            return redirect()->route('error');
        } catch (\Exception $e) {
            return redirect()->route('error');
        }
    }

    public function commentReportsView(string $username) {
        if (!Auth::check()) return redirect('/login');
        
        try {
            if(Auth::user()->username === $username){
                $commentReports = Report::where('comment_reported_id','!=',null)->with('users')->with('comments_reported')
                                    ->with('comments_reported.user')->paginate(5);
        
                $viewHTML = view('partials.reports_admin', ['reports'=>$commentReports, 'type' => 'comment'])->render();
                return response()->json(array('success' => true, 'html' => $viewHTML));
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

    public function dismissReport(Request $request){
        if (!Auth::check()) return redirect('/login');

        try {
            if(Auth::user()->isAdmin()){
                $report = Report::find($request->input('report_id'));
        
                $report->delete();

                return response()->json(array('success' => true, 'report_id' => $request->input('report_id')));
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

    public function disableUser(Request $request){
        if (!Auth::check()) return redirect('/login');
        try {

            if(Auth::user()->isAdmin()){
                $report = Report::find($request->input('report_id'));
                $user = User::find($report->user_reported_id);

                $reports = Report::where('user_reported_id','=',$report->user_reported_id)->get();

                $user->disable = true;
                $user->save();

                for($x = 0; $x < count($reports);$x++){
                    $reports[$x]->delete();
                }

                return response()->json(array('success' => true, 'report_id' => $request->input('report_id')));
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

    public function deleteComment(Request $request){
        if (!Auth::check()) return redirect('/login');
        try {

            if(Auth::user()->isAdmin()){
                    
                $report = Report::find($request->input('report_id'));
                $comment = Comment::find($report->comment_reported_id);

                //if the admin also wants to disable the user due to the comment
                if($request->input('disable') == 'true'){
                    $user = User::find($comment->user_id);
                    $user->disable = true;
                    $user->save();
                }
                
                $reports = Report::where('comment_reported_id','=',$report->comment_reported_id)->get();

                for($x = 0; $x < count($reports);$x++){
                    $reports[$x]->delete();
                }

                $comment->delete();

                return response()->json(array('success' => true, 'report_id' => $request->input('report_id')));
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

    public function commentReportView($report_id){
        if (!Auth::check()) return redirect('/login');

        try {
            if(Auth::user()->isAdmin()){

                $report = Report::where('id','=',$report_id)->with('users')->with('comments_reported')
                ->with('comments_reported.user')->first();

                $reportView = view('partials.report_comment_detail', ['report' => $report])->render();
                return response()->json(array('success' => true, 'reportView' => $reportView,'report'=>$report));
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

    public function userReportView($report_id){
        if (!Auth::check()) return redirect('/login');
        
        try {
            if(Auth::user()->isAdmin()){
            
                $report = Report::where('id','=',$report_id)->with('users')->with('comments_reported')
                ->with('comments_reported.user')->first();

                $reportView = view('partials.report_user_detail', ['report' => $report])->render();
                return response()->json(array('success' => true, 'reportView' => $reportView,'report'=>$report));
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
