<div class="content_view">
    <div class="row" id="requests_show">
        <div class="col-12">
            @foreach($requests as $request)
                @include('partials.request', ['project_id' => $project->id, 'request' => $request])
            @endforeach
        </div>
    </div>
</div>