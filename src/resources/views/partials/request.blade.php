<div data-id="{{$request->id}}"class="request row">
    <p class="col-md-6 col-12">User <strong>{{$request->user->username}}</strong> requested to join this project</p>
    <div class="option_buttons col-md-6 col-12">
        <a href="{{route('settings_accept_request', [ 'project_id' => $project_id, 'request_id' => $request->id])}}" class="btn request_accept"><i class="fas fa-check"></i></a>
        <a href="{{route('rejectRequest', [ 'project_id' => $project_id, 'request_id' => $request->id])}}" class="btn request_delete"><i class="fas fa-times"></i></a>
    </div>
</div>