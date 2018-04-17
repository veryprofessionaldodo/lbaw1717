<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $table = 'comment';


  public function task() {
    return $this->belongsTo('App\Task');
  }

  public function user() {
  	return $this->belongsTo('App\User');
  }

  public function thread() {
    return $this->belongsTo('App\Thread');
  }
}