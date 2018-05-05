<div class="member row">
    <div class="col-lg-6 col-12">
        <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png">
        <h3>{{$member->username}}</h3>
    </div>

    @if($member->pivot->iscoordinator == false)
        <div class="col-lg-6 col-12 buttons">
            <button class="btn">Promote to Coordenator</i></button>
            <button class="btn"><i class="fas fa-times"></i></button>
        </div>
    @else
    @endif

</div>