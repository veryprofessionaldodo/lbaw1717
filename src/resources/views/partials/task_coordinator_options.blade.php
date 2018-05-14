<div class="coordinator_options">

    @if($user_username !== Auth::user()->username)
        <a class="btn btn-primary claim" href="{{ route('assign_self', ['project_id' => $project->id, 'task_id' => $task->id])}}">
            Claim task</a>
    @else
        <a class="btn btn-primary claim" href="{{ route('unassign_self', ['project_id' => $project->id, 'task_id' => $task->id])}}">
            Unclaim task</a>
    @endif
    
    {{--  If task is assigned and not to the coordinator  --}}
    @if($user_username !== null && $user_username !== Auth::user()->username)
        <a class="btn btn-primary" id="unassign_user" 
            href="{{ route('unassign_other', ['project_id' => $project->id, 'task_id' => $task->id])}}">
            Unassign user from task</a>
    @else
        <button class="btn btn-primary" id="assign_user">Assign task to user</button>

        <form id="assign_user_form" class="hidden" method="POST" action="{{route('assign_other', ['project_id' => $project->id, 'task_id' => $task->id])}}">
            {{ csrf_field() }}
            <input class="form-control user_name" type="text" name="user_username" placeholder="username">
            <button class="btn btn-primary send_name"><i class="fas fa-caret-right"></i></button>
        </form>
    @endif

    <button class="btn btn-warning edit_task"><i class="fas fa-pencil-alt"></i></button>
    <button class="btn btn-danger delete_task"><i class="fas fa-trash-alt"></i></button>

</div>