@foreach($members as $member)
    @include('partials.member', ['user' => $member])
@endforeach