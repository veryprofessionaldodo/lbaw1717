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

  public function categories() {
    return $this->hasMany('App\Category', 'project_categories');
  }

  public function scopeSearch($query, $search) {
    if(!$search)
      return $query;
    return $query->whereRaw('to_tsvector(\'english\', name || \' \' || description) 
      @@ plainto_tsquery(\'english\', ?)', [$search])->where('ispublic','=',true)->
    orderBy('name');
  }

  public function threads() {
    return $this->hasMany('App\Thread');
  }

  public function topContributors(){
    $tUsers = DB::select()
		->addSelect(user.username)
		->addSelect(user.image)
		->addSelect(DB::raw(`COUNT(*) as num`))
		->from(`user`)
		->from(`task_state_record`)
		->from(`task`)
		->where(`task.project_id`, `=`, `$project_id`)
		->where(`task_state_record.task_id`, `=`, `task.id`)
		->where(`user.id`, `=`, `task_state_record.user_completed_id`)
		->where(`task_state_record.state`, `=`, `Completed`)
		->groupBy(`user.username`)
		->groupBy(`user.image`)
		->orderByRaw(`num DESC`)
		->limit(3)
    ->get();
    
    return $tUsers;
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