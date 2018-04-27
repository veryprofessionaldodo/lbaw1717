<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $table = 'project';

  public function user() {
    return $this->belongsToMany('App\User', 'project_members')->withPivot('iscoordinator');
  }

  public function sprints() {
    return $this->hasMany('App\Sprint');
  }

  public function tasks() {
    return $this->hasMany('App\Task');
  }

  public function categories() {
    return $this->hasMany('App\Category', 'project_categories');
  }

  public function scopeSearch($query, $search) {
    if(!$search)
      return $query;
    return $query->whereRaw('to_tsvector(\'english\', name || \' \' || description) 
      @@ plainto_tsquery(\'english\', ?)', [$search])->where('ispublic','=',true)->
    orderBy('name');
  }

  public function threads() {
    return $this->hasMany('App\Thread');
  }

  public function topContributors(){
    return DB::select(
      DB::raw('SELECT "user".username, "user".image, COUNT(*) AS num
      FROM "user", task_state_record, task
      WHERE task.project_id = :project_id
      AND task_state_record.task_id = task.id
      AND "user".id = task_state_record.user_completed_id
      AND task_state_record.state = :state
      GROUP BY "user".username, "user".image
      ORDER BY num DESC LIMIT 3;'), array('project_id' => $this->id, 'state' => 'Completed'));
  }

  public function zeroContributors(){
    return DB::select(
      DB::raw('SELECT "user".username, "user".image, 0 AS num
      FROM "user", task
      WHERE task.project_id = :project_id
      GROUP BY "user".username, "user".image
      ORDER BY num DESC LIMIT 3;'), array('project_id' => $this->id));
  }

public function tasksCompleted(){
  return DB::select(
    DB::raw('SELECT COUNT(*) FROM task, task_state_record
    WHERE task.project_id = :project_id 
    AND task_state_record.task_id = task.id 
    AND task_state_record.state = :state'), array('project_id' => $this->id, 'state' => 'Completed'));
}

public function sprintsCompleted(){
  return DB::select(
    DB::raw(' SELECT COUNT(*)
    FROM sprint, sprint_state_record
    WHERE sprint.project_id = :project_id
    AND sprint_state_record.sprint_id = sprint.id 
    AND sprint_state_record.state = :state'), array('project_id' => $this->id, 'state' => 'Completed'));
}

  
 
 
  

  /**
   * The user this card belongs to
   */
  /*public function user() {
    return $this->belongsTo('App\User');
  }*/

  /**
   * Items inside this card
   */
  /*public function items() {
    return $this->hasMany('App\Item');
  }*/
}