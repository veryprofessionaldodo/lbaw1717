<div class="col-12" id="sprints">
	<div class="list-group list-group-root well">

		@foreach($sprints as $sprint)
			@include('partials.sprint', ['sprint' => $sprint, 'role' => $role])
		@endforeach

	</div>
</div>