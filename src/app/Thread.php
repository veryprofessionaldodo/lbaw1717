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
}