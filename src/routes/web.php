<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Landing Page
Route::get('/', 'LandingPageController@showLandingPage'); //done

//Search public projects
Route::post('api/search', 'ProjectController@searchProject')->name('search'); //done

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login'); //done
Route::post('login', 'Auth\LoginController@login'); //done
Route::get('logout', 'Auth\LoginController@logout')->name('logout'); //done
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register'); //done
Route::post('register', 'Auth\RegisterController@register'); //done

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.request.view');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.request');

// User
Route::get('api/users/{username}', 'User\UserController@showProfile')->name('user_profile'); //done
Route::get('api/users/{username}/edit', 'User\UserController@editProfileForm')->name('edit_profile'); //done
Route::post('api/users/{username}/edit', 'User\UserController@editProfileAction')->name('edit_profile_action'); // done

Route::post('api/projects/{project_id}/request', 'User\UserController@requestJoinProject')->name('request_join_project');

//Notifications
Route::post('api/notifications/{notification_id}/dismiss','User\UserController@dismissNotification')->name('dismiss_notification');//done
Route::post('api/notifications/{notification_id}/accept','User\UserController@acceptInviteNotification')->name('accept_invite_notification');//done
Route::post('api/notifications/{notification_id}/reject','User\UserController@rejectInviteNotification')->name('reject_invite_notification');//done

Route::get('api/users/{username}/projects/new_project', 'User\UserController@createProjectForm')->name('new_project_form');
Route::post('api/users/projects', 'ProjectController@create')->name('create_project'); //done


// Project
Route::get('api/projects/{project_id}/members', 'ProjectController@projectMembersView')->name('project_members'); //done
Route::get('api/projects/{project_id}/settings', 'ProjectController@projectSettings')->name('project_settings');//done
Route::get('api/projects/{project_id}/settings/members', 'ProjectController@projectSettingsMembersView')->name('project_settings_members');//done
Route::post('api/projects/{project_id}/settings/members/{username}/remove', 'ProjectController@projectSettingsMembersRemove')->name('remove_member');//done
Route::post('api/projects/{project_id}/settings/members/{username}/promote', 'ProjectController@projectSettingsPromote')->name('promote_member');//done
Route::get('api/projects/{project_id}/settings/requests', 'ProjectController@projectSettingsRequestsView')->name('project_settings_requests');//done
Route::post('api/projects/{project_id}/settings/requests/{request_id}/accept', 'ProjectController@projectSettingsRequestsAccept')->name('settings_accept_request');//done
Route::post('api/projects/{project_id}/settings/requests/{request_id}/reject', 'ProjectController@projectSettingsRequestsReject')->name('rejectRequest');//done
Route::get('api/projects/{project_id}/statistics', 'ProjectController@projectStatisticsView')->name('project_stats');
// Route::post('api/projects/{project_id}/members', 'ProjectController@projectMembersSearch');
// Route::post('api/projects/{project_id}/settings/members', 'ProjectController@projectSettingsMembersSearch');


Route::get('api/projects/{project_id}/members/edit', 'ProjectController@editForm')->name('edit_project_form');//done
Route::post('api/projects/{project_id}/members/edit', 'ProjectController@edit')->name('edit_project');


Route::get('api/projects/{project_id}', 'ProjectController@show')->name('project'); // done

//Sprints and Tasks
Route::get('api/projects/{project_id}/sprints', 'ProjectController@sprintsView')->name('project_sprints'); //done
Route::get('api/projects/{project_id}/sprints/{sprint_id}/edit', 'SprintController@edit')->name('edit_sprint_form');
Route::post('api/projects/{project_id}/sprints/{sprint_id}/edit', 'SprintController@update')->name('edit_sprint_action');
Route::get('api/projects/{project_id}/sprints/new_sprint', 'SprintController@showForm')->name('new_sprint_form');
Route::post('api/projects/{project_id}/sprints', 'SprintController@create')->name('new_sprint');
Route::post('api/projects/{project_id}/sprints/{sprint_id}','SprintController@destroy')->name('delete_sprint');

//Tasks 
Route::get('api/projects/{project_id}/tasks', 'ProjectController@taskView')->name('project_tasks');
Route::get('api/projects/{project_id}/tasks/{task_id}', 'TaskController@show')->name('task_page');
// Route::get('api/projects/{project_id}/tasks/{task_id}/edit', 'ProjectController@taskEditForm');
Route::post('api/projects/{project_id}/tasks/{task_id}/edit', 'TaskController@edit')->name('edit_task');
Route::delete('api/projects/{project_id}/tasks/{task_id}', 'TaskController@destroy')->name('delete_task');
Route::post('api/projects/{project_id}/tasks/{task_id}/complete', 'TaskController@update')->name('update_task');
Route::post('api/projects/{project_id}/tasks/{task_id}/assign', 'TaskController@assignSelf')->name('assign_self');
Route::post('api/projects/{project_id}/tasks/{task_id}/unassign', 'TaskController@unassignSelf')->name('unassign_self');
Route::post('api/projects/{project_id}/tasks/{task_id}/assign_other', 'TaskController@assign')->name('assign_other');
Route::post('api/projects/{project_id}/tasks/{task_id}/unassign_other', 'TaskController@unassign')->name('unassign_other');
Route::post('api/projects/{project_id}/tasks', 'TaskController@store')->name('new_task');
Route::post('api/projects/{project_id}/tasks/{task_id}/comments', 'CommentController@storeCommentTask')->name('create_comment_task'); //done
Route::get('api/projects/{project_id}/tasks/{task_id}/comments/{comment_id}/edit', 'CommentController@edit')->name('editTaskComment');
// Route::post('api/projects/{project_id}/tasks/{task_id}/comments/{comment_id}/edit', 'ProjectController@editCommentAction');
Route::post('api/projects/{project_id}/tasks/{task_id}/comments/{comment_id}', 'CommentController@destroy')->name('deleteCommentTask'); //done

//Project Forum
Route::get('projects/{id}/threads', 'ThreadController@list')->name('forum'); //done
Route::get('projects/{id}/threads/create', 'ThreadController@create')->name('new_thread_form'); //done
Route::post('projects/{id}/threads/create', 'ThreadController@store')->name('new_thread_action'); //done
Route::get('projects/{id}/threads/{thread_id}', 'ThreadController@show')->name('thread'); //done
Route::get('projects/{id}/threads/{thread_id}/edit', 'ThreadController@edit')->name('edit_thread_form');//done
Route::post('projects/{id}/threads/{thread_id}', 'ThreadController@update')->name('edit_thread_action');//done
Route::delete('projects/{id}/threads/{thread_id}', 'ThreadController@destroy')->name('deleteThread'); //done

Route::post('projects/{id}/threads/{thread_id}/comments', 'CommentController@store')->name('new_comment'); //done
Route::post('projects/{id}/threads/{thread_id}/comments/{comment_id}', 'CommentController@destroy')->name('deleteCommentThread'); //done
Route::post('projects/{id}/threads/{thread_id}/comments/{comment_id}/edit', 'CommentController@edit')->name('editCommentThread');

//Admin Administraton, Report and Static Pages
Route::get('api/admin/{username}', 'AdminController@showAdminPage'); // done
Route::get('api/admin/{username}/reports/comments', 'AdminController@commentReportsView')->name('admin_comments'); //done
Route::get('api/admin/{username}/reports/users', 'AdminController@userReportsView')->name('admin_users'); //done

Route::get('admin/reports/comments/{comment_report_id}', 'AdminController@commentReportView')->name('commentReportView');//done
Route::get('admin/reports/users/{user_report_id}', 'AdminController@userReportView')->name('userReportView');//done
Route::post('admin/reports/{report_id}', 'AdminController@dismissReport')->name('dismissReport'); //done
Route::post('admin/reports/users/{report_id}', 'AdminController@disableUser')->name('disableUser'); //done
Route::post('admin/reports/comments/{report_id}', 'AdminController@deleteComment')->name('deleteCommentReport'); //done
/*Route::delete('admin/projects/{project_id}', 'AdminController@deleteProject');
*/
Route::get('actions/reports/comments/{comment_id}', 'ReportController@commentReportForm')->name('comment_report_form'); //done
Route::get('actions/reports/users/{username}', 'ReportController@userReportForm')->name('user_report_form'); //done
Route::post('actions/reports/users/{username}', 'ReportController@createReport')->name('create_user_report'); //done
Route::post('actions/reports/comments{comment_id}', 'ReportController@createReport')->name('create_comment_report'); //done

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home');
