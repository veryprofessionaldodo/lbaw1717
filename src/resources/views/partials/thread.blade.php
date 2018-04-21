<thead>
	<tbody>
        <tr class="table-dark">
            <th scope="row"><a href="{{route('thread',['id' => $project->id, 'thread_id' => $thread->id])}}">{{ $thread->name }}</a></th>
			<td><a href="{{ route('user_profile', ['username' => $thread->user->username])}}">{{ $thread->user->username}}</a> </td>
			
			<?php 
				$aux = new \DateTime($thread->date);
				$date = $aux->format('d-m-Y');
			?>

            <td>{{ $date }}</td>
        </tr>
    </tbody>
</thead>