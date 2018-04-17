<div class="container new_project">
    <h2>New Project</h2>
    <div class="form_area">
        <label>Project Name: </label>
        <input type="text" class="form-control" name="project_name" placeholder="Project Name">
    </div>
    <div class="form_area">
        <label>Project Description: </label>
        <input type="text" class="form-control" name="project_description" placeholder="Project Description">
    </div>
    <div class="form_area custom-checkbox">
        <label>Public: </label>
        <input type="checkbox" id="public">
    </div>
    <div class="form_area">
        <label>Categories (can be more than one):</label>
        <select name="categories" class="form-control" multiple>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{$category->name}}</option>
            @endforeach
        </select>
    </div>
    <div id="form_options">
        <a href="{{ route('create_project', ['user_id' => Auth::user()->id])}}" class="btn btn-success">Submit</a>
        <a href="{{ route('user_profile', ['username' => Auth::user()->username])}}" class="btn btn-danger">Cancel</a>
    </div>
</div>