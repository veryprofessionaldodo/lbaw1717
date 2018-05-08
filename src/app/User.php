<?php

namespace App;

use Illuminate\Support\Facades\DB;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    public $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'email', 'image', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The cards this user owns.
     */
    public function projects() {
       return $this->belongsToMany(Project::class, 'project_members')->withPivot('iscoordinator');
    }

    public function comments() {
        return $this->hasMany('App\Comment');
    }

    public function threads(){
        return $this->hasMany('App\Thread','user_creator_id');
    }

    public function invites(){
        return $this->hasMany('App\Invite');
    }
    
    public function task_state_record() {
        return $this->hasMany('App\TaskStateRecord', 'user_completed_id');
    }

    public function isAdmin(){
        return $this->isadmin; // this looks for an admin column in your users table
    }

    public function isDisable(){
        return $this->disable;  //this looks for an disable column in your users table
    }

    public function isProjectMember(Project $project) {
        return $this->projects()->get()->contains($project);
    }

    public function isCoordinator(int $project_id) {
        return $this->projects()->find($project_id)->pivot->iscoordinator;
    }

    public static function userNotifications() {
      // Add comments and reports as well
      return DB::table('notification')
              ->join('user','user.id','=','notification.user_id')
              ->join('project','project.id','=','notification.project_id')
              ->where('user_id','=',Auth::user()->id)
              ->select('user.username','project.name','notification.*')->get();
    }

    public function userProjects() {
        return $this->projects()->withCount('sprints')->withCount('user')->paginate(5);
    }

    public function userPublicProjects() {
        return $this->projects()->where('project.ispublic','=',TRUE)->withCount('sprints')->withCount('user')->paginate(5);
    }

    public function taskCompletedThisWeek() {
        return DB::select(
          DB::raw('SELECT COUNT(id) FROM task_state_record
            WHERE user_completed_id = :user_id AND state = :state
            AND (SELECT extract(week FROM task_state_record.date)) = 
            (select extract(week from current_date))'), array('user_id' => $this->id, 'state' => 'Completed'));
    }

    public function taskCompletedThisMonth() {
        return DB::select(
          DB::raw('SELECT COUNT(id) FROM task_state_record
            WHERE user_completed_id = :user_id AND state = :state
            AND (SELECT extract(month FROM task_state_record.date)) = 
            (select extract(month from current_date))'), array('user_id' => $this->id, 'state' => 'Completed'));
    }

    public function sprintsContributedTo() {
        return DB::select(
          DB::raw('SELECT COUNT(task.sprint_id) FROM task_state_record, task
            WHERE task_state_record.user_completed_id = :user_id
            AND task_state_record.state != :state
            AND task_state_record.task_id = task.id'), array('user_id' => $this->id, 'state' => 'Created'));
    }
    
}
