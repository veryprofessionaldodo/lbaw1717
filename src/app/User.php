<?php

namespace App;

use Illuminate\Support\Facades\DB;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    public $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'email', 'image', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',/* 'remember_token',*/
    ];

    /**
     * The cards this user owns.
     */
    public function projects() {
      return $this->belongsToMany(Project::class, 'project_members');
    }

    /*public function role() {
        return $this->belongsToMany(ProjectMembers::la)
    }*/
}
