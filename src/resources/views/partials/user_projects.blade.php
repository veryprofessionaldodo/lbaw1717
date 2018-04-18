@each('partials.user_project', $projects, 'project')

<div id="pagination_section">
    {{$projects->links()}}
</div>
