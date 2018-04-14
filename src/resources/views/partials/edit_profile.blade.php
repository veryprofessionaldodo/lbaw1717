<div class="container edit_profile">
	<h2>Edit Profile</h2>
	<div class="form_area">
		<label>Name: </label>
		<input type="text" class="form-control" name="user_name" value="{{ Auth::user()->name }}">
	</div>
	<div class="form_area">
		<label>Username: </label>
		<input type="text" class="form-control" name="user_username" value="{{ Auth::user()->username }}">
	</div>
	<div class="form_area">
		<label>Email: </label>
		<input type="email" class="form-control" name="user_email" value="{{ Auth::user()->email }}">
	</div>
	<div class="form_area">
		<label>Image URL: </label>
		<input type="text" class="form-control" name="user_image" value="{{ Auth::user()->image }}"> <!-- Change this later TODO -->
	</div>
	<div id="form_options">
		<a href="{{ route('edit_profile_action', ['username' => Auth::user()->username])}}" class="btn btn-success">Submit</a>
		<a href="{{ route('user_profile', ['username' => Auth::user()->username])}}" class="btn btn-danger">Cancel</a>
	</div>
</div>