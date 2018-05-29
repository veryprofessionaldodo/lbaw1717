<div class="container new_sprint">
    <form method="POST" action="{{route('new_sprint', ['project_id' => $project_id])}}">
        {{ csrf_field()}}
        <h2>New Sprint</h2>
        <div class="form_area">
            <label>Sprint Name: </label>
            <input type="text" class="form-control" name="name" placeholder="Sprint Name" required>
        </div>
        <div class="form_area">
            <label>Deadline: </label>
            <input type="date" class="form-control" name="deadline" required>
        </div>
        <div class="form_area">
            <label>Maximum Effort: </label>
            <input type="number" class="form-control" name="effort" min=1 max=20 required>
        </div>
        <div id="form_options">
            <button type="submit" class="btn btn-success">Submit</button>
            <a href="{{ route('project', ['project_id' => $project_id])}}" class="btn btn-danger">Cancel</a>
        </div>
    </form>
</div>