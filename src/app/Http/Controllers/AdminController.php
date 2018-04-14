<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\User;
use App\Report;


class AdminController extends Controller {

	public function showAdminPage(string $username){
		if (!Auth::check()) return redirect('/login');

        $userReports = Report::where('user_reported_id','!=',null)->with('users')->with('users_reported')->get();

		return view('pages/admin_page',['reports' => $userReports, 'type' => 'user']);
	}

    public function userReportsView(string $username) {
        if (!Auth::check()) return redirect('/login');

        $userReports = Report::where('user_reported_id','!=',null)->with('users')->with('users_reported')->get();

        $viewHTML = view('partials.reports_admin', ['reports'=>$userReports, 'type' => 'user'])->render();
        return response()->json(array('success' => true, 'html' => $viewHTML));
    }

    public function commentReportsView(string $username) {
        if (!Auth::check()) return redirect('/login');

        $commentReports = Report::where('comment_reported_id','!=',null)->with('users')->with('comments_reported')->with('comments_reported.user')->get();

        $viewHTML = view('partials.reports_admin', ['reports'=>$commentReports, 'type' => 'comment'])->render();
        return response()->json(array('success' => true, 'html' => $viewHTML));
    }

}
