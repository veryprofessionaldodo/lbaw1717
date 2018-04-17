<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $table = 'category';

  public function projects() {
    return $this->belongsToMany('App\Project', 'project_categories');
  }
}