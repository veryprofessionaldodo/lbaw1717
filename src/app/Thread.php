<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $table = 'thread';


  public function project() {
    return $this->belongsTo('App\Project');
  }

  public function comments() {
  	return $this->hasMany('App\Comment');
  }

  public function user() {
  	return $this->belongsTo('App\User','user_creator_id');
  }

  function canBeEdited(User $user){
    return $user->isAdmin() || $this->user_creator_id == $user->id;
  }
}