<?php

namespace App\Policies;

use App\User;
use App\Project;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function not_authorized(User $user, Project $project)
    {
      // TODO: Change this!!

      /*if($user->projects()->get()->contains($project))
        return true;
      else if($project->ispublic)
        return true;*/
      return false;
    }

    public function list(User $user)
    {
      // Any user can list its own projects
      return Auth::check();
    }

    public function create(User $user)
    {
      // Any user can create a new card
      return Auth::check();
    }

    public function delete(User $user, Project $project)
    { 
      // TODO: Change this!!
      // Only a card owner can delete it
      return $user->id == $project->user_id;
    }
}
