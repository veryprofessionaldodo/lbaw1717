<div data-id="{{$member->username}}" class="member-row">
    <div class="col-lg-6 col-12">
        <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png">
        <h3>{{$member->username}}</h3>
    </div>

    @if($member->pivot->iscoordinator == false)
        <div class="col-lg-6 col-12 buttons">
            <a href="{{route('promote_member',['project_id' => $project_id,'username' => $member->username])}}" class="btn promote">Promote to Coordenator</i></a>
            <a href="{{route('remove_member',['project_id' => $project_id,'username' => $member->username])}}" class="btn remove"><i class="fas fa-times"></i></a>
        </div>
    @else
    @endif

</div>