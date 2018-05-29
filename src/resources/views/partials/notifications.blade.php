@foreach($notifications as $notification)
  <li data-id="{{$notification->id}}" class="row">
  @if($notification->notification_type == 'invite')
      <div class="notification_content col-9">
        <p>Invite to <strong>{{ $notification->name }}</strong> by <em>{{ $notification->username }}</em></p>
      </div>
      <div class="notification_options col-3">
        <a href="{{ route('accept_invite_notification', ['notification_id' => $notification->id])}}" class="btn accept"><i class="fas fa-check"></i></a>
        <a href="{{ route('reject_invite_notification', ['notification_id' => $notification->id])}}" class="btn reject"><i class="fas fa-times"></i></a>
      </div>
  @elseif($notification->notification_type == 'comment')
      <div class="notification_content col-12">
        <a href="#">A comment has been written in your thread</a>
      </div>
      <div class="notification_options col-3">
          <a href="{{ route('dismiss_notification', ['notification_id' => $notification->id])}}" class="btn dismiss"><i class="fas fa-times"></i></a>
      </div>
  @elseif($notification->notification_type == 'commentreported')
      <div class="notification_content col-12">
        <a href="#">Your comment has been reported</a>
      </div>
      <div class="notification_options col-3">
          <a href="{{ route('dismiss_notification', ['notification_id' => $notification->id])}}" class="btn dismiss"><i class="fas fa-times"></i></a>
      </div>
  @elseif($notification->notification_type == 'promotion')
      <div class="notification_content col-12">
        <a href="#">You were promoted in project <strong>{{ $notification->name }}</strong></a>
      </div>
      <div class="notification_options col-3">
          <a href="{{ route('dismiss_notification', ['notification_id' => $notification->id])}}" class="btn dismiss"><i class="fas fa-times"></i></a>
      </div>
  @elseif($notification->notification_type == 'removedfromproject')
      <div class="notification_content col-12">
        <a href="#">You were removed from project <strong>{{ $notification->name }}</strong></a>
      </div>
      <div class="notification_options col-3">
          <a href="{{ route('dismiss_notification', ['notification_id' => $notification->id])}}" class="btn dismiss"><i class="fas fa-times"></i></a>
      </div>
  @endif
  </li>
@endforeach