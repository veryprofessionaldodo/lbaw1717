@foreach($notifications as $notification)
  @if($notification->notification_type == 'invite')
    <li class="row">
      <div class="notification_content col-9">
        <p>Invite to <strong>{{ $notification->name }}</strong> by <em>{{ $notification->username }}</em></p>
      </div>
      <div class="notification_options col-3">
        <a class="btn"><i class="fas fa-check"></i></a>
        <a class="btn"><i class="fas fa-times"></i></a>
      </div>
    </li>
  @elseif($notification->notification_type == 'comment')
    <li class="row">
      <div class="notification_content col-12">
        <a href="#">A comment has been written in your thread</a>
      </div>
    </li>
  @elseif($notification->notification_type == 'commentreported')
    <li class="row">
      <div class="notification_content col-12">
        <a href="#">Your comment has been reported</a>
      </div>
    </li>
  @elseif($notification->notification_type == 'promotion')
    <li class="row">
      <div class="notification_content col-12">
        <a href="#">You were promoted in project <strong>{{ $notification->name }}</strong></a>
      </div>
    </li>
  @elseif($notification->notification_type == 'removedfromproject')
    <li class="row">
      <div class="notification_content col-12">
        <a href="#">You were removed from project <strong>{{ $notification->name }}</strong></a>
      </div>
    </li>
  @endif
  <!-- Request? -->
@endforeach