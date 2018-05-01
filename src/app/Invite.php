<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $table = 'invite';

  public function user() {
  	return $this->belongsTo('App\User', 'user_invited_id');
  }

  public function project() {
    return $this->belongsTo('App\Thread');
  }
}