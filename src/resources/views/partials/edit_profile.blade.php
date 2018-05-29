<div class="container edit_profile">
	<form method="POST" action="{{ route('edit_profile_action', ['username' => Auth::user()->username, 'files' => true]) }}" enctype="multipart/form-data">
		{{ csrf_field()}}
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
			<label>Image File: </label>
			<input type="file" class="form-control" name="user_image">
		</div>
		<div id="form_options">
			<button type="submit" class="btn btn-success">Submit</button>
			<a href="{{ route('user_profile', ['username' => Auth::user()->username])}}" class="btn btn-danger">Cancel</a>
		</div>
	</form>
</div>