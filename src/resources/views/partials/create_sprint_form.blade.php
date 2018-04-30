<div class="container new_sprint">
    <h2>New Sprint</h2>
    <div class="form_area">
        <label>Sprint Name: </label>
        <input type="text" class="form-control" name="name" placeholder="Sprint Name">
    </div>
    <div class="form_area">
        <label>Deadline: </label>
        <input type="date" class="form-control" name="deadline" >
    </div>
    <div id="form_options">
        <a href="{{ route('new_sprint', ['project_id' => $project_id])}}" class="btn btn-success">Submit</a>
        <a href="{{ route('project', ['project_id' => $project_id])}}" class="btn btn-danger">Cancel</a>
    </div>
</div>