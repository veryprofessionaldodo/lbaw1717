@foreach($members as $member)
    @include('partials.settings_member', ['project_id' => $project->id, 'member' => $member])
@endforeach