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
Route::get('/', 'LandingPageController@showLandingPage');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// User
Route::get('api/users/{username}', 'User\UserController@showProfile');
Route::get('api/users/{username}/edit', 'User\UserController@showEditProfileForm');
Route::post('api/users/{username}/edit', 'User\UserController@editProfile');

Route::post('api/users/projects/accept_invite', 'User\UserController@acceptInvite');
Route::post('api/users/projects/unsigned_project', 'User\UserController@unsignProject');
Route::post('api/users/projects/search_project', 'User\UserController@searchUserProject');

Route::put('api/users/projects/new_project', 'User\UserController@newProject');

/*
// Project
Route::get('projects/{id}/visitor', 'ProjectController@projectViewVisitor');
Route::get('projects/{id}/members', 'ProjectController@projectMembersList');
Route::get('projects/{id}/settings/members', 'ProjectController@projectSettingsMembersList');
Route::delete('projects/{id}/settings/members/{id}/remove', 'ProjectController@projectSettingsMembersRemove');
Route::get('projects/{id}/settings/requests', 'ProjectController@projectSettingsRequestsList');
Route::post('projects/{id}/settings/requests/{id}/accept', 'ProjectController@projectSettingsRequestsAccept');
Route::post('projects/{id}/edit', 'ProjectController@projectSettingsRequestsAccept');
Route::get('projects/{id}/statistics', 'ProjectController@projectStatisticsView');
Route::post('projects/{id}/members', 'ProjectController@projectMembersSearch');
Route::post('projects/{id}/settings/members', 'ProjectController@projectSettingsMembersSearch');


//Sprints and Tasks
Route::get('projects/{id}/sprints', 'ProjectController@sprintsView');
Route::get('projects/{id}/sprints/{sprint_id}/edit', 'ProjectController@sprintEditForm');
Route::post('projects/{id}/sprints/{sprint_id}', 'ProjectController@sprintEdit');
Route::get('projects/{id}/sprints/{sprint_id}/tasks/{task_id}', 'ProjectController@taskPageView');
Route::get('projects/{id}/sprints/{sprint_id}/tasks/{task_id}/edit', 'ProjectController@taskEditForm');
Route::post('projects/{id}/sprints/{sprint_id}/tasks/{task_id}', 'ProjectController@taskEditAction');
Route::get('projects/{id}/sprints/new_sprint', 'ProjectController@newSprintForm');
Route::put('projects/{id}/sprints/', 'ProjectController@newSprint');
Route::put('projects/{id}/sprints/{sprint_id}/tasks/', 'ProjectController@newTask');
Route::put('projects/{id}/sprints/{sprint_id}/tasks/{task_id}/comments/', 'ProjectController@newComment');
Route::get('projects/{id}/sprints/{sprint_id}/tasks/{task_id}/comments/{comment_id}/edit','ProjectController@editCommentForm');
Route::post('projects/{id}/sprints/{sprint_id}/tasks/{task_id}/comments/{comment_id}','ProjectController@editCommentAction');
Route::post('projects/{id}/sprints/{sprint_id}/tasks/{task_id}/assign','ProjectController@taskAssignUser');
Route::post('projects/{id}/sprints/{sprint_id}/tasks/{task_id}/unassign','ProjectController@taskUnassignUser');
Route::delete('projects/{id}/sprints/{sprint_id}','ProjectController@deleteSprint');
Route::delete('projects/{id}/sprints/{sprint_id}/task/{task_id}','ProjectController@deleteTask');
Route::delete('projects/{id}/sprints/{sprint_id}/task/{task_id}/comments/{comment_id}','ProjectController@deleteComment');
Route::post('projects/{id}/sprints/{sprint_id}/task/{task_id}/complete','ProjectController@completeTask');

//Tasks 
Route::get('projects/{id}/tasks', 'ProjectController@taskView');
Route::get('projects/{id}/tasks/{task_id}', 'ProjectController@taskPageView');
Route::get('projects/{id}/tasks/{task_id}/edit', 'ProjectController@taskEditForm');
Route::post('projects/{id}/tasks/{task_id}', 'ProjectController@taskEditAction');
Route::delete('projects/{id}/tasks/{task_id}', 'ProjectController@deleteTask');
Route::post('projects/{id}/tasks/{task_id}/complete', 'ProjectController@completeTask');
Route::post('projects/{id}/tasks/{task_id}/assign', 'ProjectController@taskAssignUser');
Route::post('projects/{id}/tasks/{task_id}/unassign', 'ProjectController@taskUnassignUser');
Route::put('projects/{id}/tasks/', 'ProjectController@newTask');
Route::put('projects/{id}/tasks/{task_id}/comments', 'ProjectController@newComment');
Route::get('projects/{id}/tasks/{task_id}/comments/{comment_id}/edit', 'ProjectController@editCommentForm');
Route::post('projects/{id}/tasks/{task_id}/comments/{comment_id}', 'ProjectController@editCommentAction');
Route::delete('projects/{id}/tasks/{task_id}/comments/{comment_id}', 'ProjectController@deleteComment');

//Project Forum
Route::get('projects/{id}/threads', 'ProjectController@threadsView');
Route::get('projects/{id}/threads/create', 'ProjectController@threadsCreateForm');
Route::put('projects/{id}/threads', 'ProjectController@createThread');
Route::get('projects/{id}/threads/{thread_id}', 'ProjectController@threadPageView');
Route::get('projects/{id}/threads/{thread_id}/edit', 'ProjectController@threadEditForm');
Route::post('projects/{id}/threads/{thread_id}', 'ProjectController@threadEditAction');
Route::put('projects/{id}/threads/{thread_id}/comments', 'ProjectController@newThreadComment');
Route::get('projects/{id}/threads/{thread_id}/comments/{comment_id}/edit', 'ProjectController@threadCommentEditForm');
Route::post('projects/{id}/threads/{thread_id}/comments/{comment_id}', 'ProjectController@threadCommentEditAction');
Route::delete('projects/{id}/threads/{thread_id}', 'ProjectController@deleteThread');
Route::delete('projects/{id}/threads/{thread_id}/comments/{comment_id}', 'ProjectController@deleteComment');

//Admin Administraton, Report and Static Pages
*/Route::get('admin/reports/comments', 'AdminController@commentReportsView');
/*Route::get('admin/reports/users', 'AdminController@userReportsView');
Route::get('admin/reports/comments/{comment_report_id}', 'AdminController@commentReportView');
Route::get('admin/reports/users/{user_report_id}', 'AdminController@userReportView');
Route::delete('admin/reports/{report_id}', 'AdminController@deleteReport');
Route::post('admin/reports/users/{report_id}', 'AdminController@disableUser');
Route::delete('admin/reports/comments/{report_id}', 'AdminController@deleteComment');
Route::delete('admin/projects/{project_id}', 'AdminController@deleteProject');

Route::get('actions/reports/comments', 'ReportController@commentReportForm');
Route::get('actions/reports/users', 'ReportController@userReportForm');
Route::put('actions/reports/users', 'ReportController@createUserReport');
Route::put('actions/reports/comments', 'ReportController@createCommentReport');
*/


/*
// Cards
Route::get('cards', 'CardController@list');
Route::get('cards/{id}', 'CardController@show');

// API
Route::put('api/cards', 'CardController@create');
Route::delete('api/cards/{card_id}', 'CardController@delete');
Route::put('api/cards/{card_id}/', 'ItemController@create');
Route::post('api/item/{id}', 'ItemController@update');
Route::delete('api/item/{id}', 'ItemController@delete');
*/