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