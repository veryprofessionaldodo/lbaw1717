<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Task_state_record extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $table = 'task_state_record';


  public function task() {
    return $this->belongsTo('App\Task');
  }

  public function user() {
      return $this->belongsTo('App\User', 'user_completed_id');
  }
}