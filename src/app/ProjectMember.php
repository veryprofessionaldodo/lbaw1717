<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $table = 'project_members';

  
}