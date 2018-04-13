<?php

namespace App;

use Illuminate\Support\Facades\DB;

use Illuminate\Notifications\Notifiable;
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
       return $this->belongsToMany(Project::class, 'project_members');
    }

    // nao funciona
    /*public function Notifications() {
      return $this->hasMany('App\Notification', 'user_id');
    }*/

    public function userNotifications() {
      // Add comments and reports as well
      return DB::table('notification')
              ->join('user','user.id','=','notification.user_id')
              ->join('project','project.id','=','notification.project_id')
              ->where('user_id','=',$this->id)
              ->select('user.username','project.name','notification.*')->get();
    }

    public function userProjects(int $n) {
        return DB::select(
          DB::raw('SELECT project.name, project.description, project_members.iscoordinator, num.num_members, sprints.sprints_num
            FROM "user", project_members, project
            INNER JOIN 
            (SELECT project_id, COUNT(project_id) AS num_members
            FROM project_members GROUP BY project_members.project_id) num 
            ON project.id = num.project_id
            INNER JOIN
            (SELECT project_id, COUNT(*) AS sprints_num FROM sprint
            GROUP BY project_id) sprints 
            ON project.id = sprints.project_id
            WHERE "user".username = :username AND project_members.user_id = "user".id 
            AND project_members.project_id = project.id AND num.project_id = project.id
            LIMIT 5 OFFSET :n'), array('username' => $this->username, 'n' => $n)
        );
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
