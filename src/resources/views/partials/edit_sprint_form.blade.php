<?php
    $date = new DateTime($sprint->deadline);
?>
<div class="row">
    <div class="col-12">
        <h5>Edit Sprint: <strong>{{ $sprint->name }}</strong></h5>
    </div>
</div>

<div class="container new_sprint">

    <form method="POST" action="{{route('edit_sprint_action', ['project_id' => $project->id, 'sprint_id' => $sprint->id])}}">
        {{ csrf_field()}}
        
        <h2>Edit Sprint</h2>
        <div class="form_area">
            <label>Sprint Name: </label>
            <input type="text" class="form-control" name="name" value="{{ $sprint->name }}">
        </div>
        <div class="form_area">
            <label>Deadline: </label>
            <input type="date" class="form-control" name="deadline" value="{{ $date->format('Y-m-d') }}" >
        </div>
        <div class="form_area">
            <label>Maximum Effort: </label>
            <input type="number" class="form-control" name="effort" min=1 max=20 value="{{$sprint->effort}}">
        </div>
        <div id="form_options">
            <button type="submit" class="btn btn-success">Submit</button>
            <a href="{{ route('project', ['project_id' => $project->id])}}" class="btn btn-danger">Cancel</a>
        </div>
    </form>
</div>