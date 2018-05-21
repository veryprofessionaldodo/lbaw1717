<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $table = 'notification';

  public function user() {
    return $this->belongsTo('App\User','user_id');
  }

}
