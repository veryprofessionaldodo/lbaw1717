		<div class="row" id="members">
			<div class="row">
				<div class="col-lg-6 col-12">
					<div class="user_search">
						<label>Invite new team member: </label>
						<input type="text" class="form-control" name="new_team_member" placeholder="Username">
						<button class="btn btn-primary" type="submit">Send</button>
					</div>
				</div>
				<div class="col-lg-6 col-12">
					<div class="user_search">
						<label>Search team member: </label>
						<input type="text" class="form-control" name="new_team_member" placeholder="Username">
						<button class="btn btn-primary" type="submit">Search</button>
					</div>
				</div>
			</div>

			<div class="col-12">
		    	@foreach($members as $member)
						@include('partials.settings_member', ['project_id' => $project->id, 'member' => $member])
					@endforeach
		    </div>
		</div>