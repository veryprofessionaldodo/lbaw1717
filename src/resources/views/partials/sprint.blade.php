<!-- Sprint -->
<div class="list-group-item">
	<a data-toggle="collapse" href="#sprint-1"><i class="fas fa-sort-down"></i>{{$sprint->name}}</a>

	<?php 
		$a = new \DateTime('now'); 
		$b = new \DateTime($sprint->deadline);
		$diff = $a->diff($b);
		$diff = $diff->format('%d');
	?>
		<p>{{ $diff }} days until deadline</p>
	<span class="badge badge-primary badge-pill">{{ sizeof($sprint->tasks)}}</span>
</div>
<!-- Tasks -->
<div class="list-group collapse" id="sprint-1">

	@each('partials.task', $sprint->tasks, 'task');
	
</div>