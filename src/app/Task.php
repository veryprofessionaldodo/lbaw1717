<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $table = 'task';


  public function project() {
    return $this->belongsTo('App\Project');
  }

  public function sprint() {
    return $this->belongsTo('App\Sprint');
  }

  public function comments() {
  	return $this->hasMany('App\Comment');
  }

  public function task_state_records(){
    return $this->hasMany('App\Task_state_record');
  }

  public function userAssigned() {
  	return DB::select(
  		DB::raw('SELECT "user".username, "user".image, task.id FROM task, "user", task_state_record
				WHERE task.id = :task_id AND task.id = task_state_record.task_id 
				AND task_state_record.state = :state1
				AND task_state_record.user_completed_id = "user".id
				AND NOT EXISTS (SELECT * FROM task_state_record a WHERE a.task_id = task.id 
				AND a.user_completed_id = "user".id
				AND a.state = :state2 AND a.date > task_state_record.date)'), array('task_id' => $this->id, 'state1' => 'Assigned', 'state2' => 'Unassigned'));
  }
}