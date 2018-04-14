<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $table = 'report';


  public function users() {
    return $this->belongsTo('App\User', 'user_id');
  }

  public function users_reported() {
    return $this->belongsTo('App\User', 'user_reported_id');
  }

  public function comments_reported() {
    return $this->belongsTo('App\Comment', 'comment_reported_id');
  }


}