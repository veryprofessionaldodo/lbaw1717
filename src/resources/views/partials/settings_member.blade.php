<div class="member row">
    <div class="col-lg-6 col-12">
        <img src="res/profile/profile-pic.png">
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