<div class="container new_project">
    <h2>New Project</h2>
    <div class="form_area">
        <label>Project Name: 
            <span data-toggle="tooltip" data-placement="top" title="Your Project Name"><i class="fas fa-info-circle"></i></span>
        </label>
        <input type="text" class="form-control" name="project_name" placeholder="Project Name">
    </div>
    <div class="form_area">
        <label>Project Description: 
            <span data-toggle="tooltip" data-placement="top" title="Your project objectives and structure"><i class="fas fa-info-circle"></i></span>
        </label>
        <input type="text" class="form-control" name="project_description" placeholder="Project Description">
    </div>
    <div class="form_area custom-checkbox">
        <label>Public: 
            <span data-toggle="tooltip" data-placement="top" title="Check if you want your project visible to everyone"><i class="fas fa-info-circle"></i></span>
        </label>
        <input type="checkbox" id="public">
    </div>
    <div class="form_area">
        <label>Categories (can be more than one):
            <span data-toggle="tooltip" data-placement="top" title="To select more than one category, press CTRL and select the categories you want">
                <i class="fas fa-info-circle"></i></span>
        </label>
        <!--MAC::Hold down the control (ctrl) button to select multiple options-->  
        <select name="categories[]" class="form-control" multiple>
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