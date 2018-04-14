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