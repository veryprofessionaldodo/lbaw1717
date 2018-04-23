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

class ReportController extends Controller
{

    public function userReportForm(string $username){

        $user = User::where('username',$username)->get();
        $notifications = $user[0]->userNotifications();

        return view('pages/forum',['user_reported' => $user_reported, 'notifications' => $notifications]);
    }
}
