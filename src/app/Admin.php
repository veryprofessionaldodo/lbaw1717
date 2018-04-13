<?php

namespace App;

use Illuminate\Support\Facades\DB;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    public $table = 'administrator';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','password',
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
   /* public function projects() {
        return $this->belongsToMany(Project::class, 'project_members');
    }*/

    public function userProjects(int $n) {
        return DB::select(DB::raw('SELECT * FROM report WHERE type = "commentReported" LIMIT 10 OFFSET :n;'), array('n' => $n));
    }
}
