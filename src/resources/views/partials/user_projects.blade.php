@each('partials.user_project', $projects, 'project')

<div id="pagination_section" class="col-12">
    <ul class="pagination">
    @if($n != 1)
        <li class="page-item disabled">
            <a class="page-link" href="{{ route('previous_page_user')}}">&laquo;</a>
        </li>
    @endif
    @if($n != $numProjects)
        <li class="page-item">
            <a class="page-link" href="{{ route('next_page_user')}}">&raquo;</a>
        </li>
    @endif
    </ul>
</div>