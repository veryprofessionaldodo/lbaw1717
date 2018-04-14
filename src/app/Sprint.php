<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $table = 'sprint';


  public function project() {
    return $this->belongsTo('App\Project');
  }

  public function tasks() {
    return $this->hasMany('App\Task');
  }
}