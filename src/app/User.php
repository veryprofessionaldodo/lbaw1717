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
        'password',/* 'remember_token',*/
    ];

    /**
     * The cards this user owns.
     */
    public function projects() {
        return $this->belongsToMany(Project::class, 'project_members');
    }

    public function userProjects(string $username, int $n) {
        return DB::select(DB::raw('SELECT project.name, project.description, project_members.iscoordinator, num.num_members, sprints.sprints_num
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
                                    LIMIT 5 OFFSET :n'), array('username' => $username, 'n' => $n));
    }

    public function getUserProjects(string $username, int $n){
      return DB:raw('SELECT project.name, project.description, project_members.isCoordinator
                      , num.num_members, sprints.sprints_num
                      FROM "user", project_members, project
                        INNER JOIN 
                        (SELECT project_id, COUNT(project_id) AS num_members
                        FROM project_members GROUP BY project_members.project_id) num 
                        ON project.id = num.project_id
                        INNER JOIN
                        (SELECT project_id, COUNT(*) AS sprints_num FROM sprint
                        GROUP BY project_id) sprints 
                        ON project.id = sprints.project_id
                      WHERE "user".username = $username AND project_members.user_id = "user".id 
                      AND project_members.project_id = project.id AND num.project_id = project.id
                      LIMIT 5 OFFSET $n;')
        /*return DB::select('project.name', 'project.description', 'project_members.isCoordinator')
                      ->from('project')
                      ->from('project_members')
                      ->from('user')
                      ->where('user.username','=','username')
                      ->where('project_members.user_id','=','user.id')
                      ->where('project_members.project_id','=','project.id')
                      ->take(5)
                      ->skip('n')
                      ->setBindings([$username,$n])
                      ->get();*/
        //return $query;
    }

    /*public function role() {
        return $this->belongsToMany(ProjectMembers::la)
    }*/
}
