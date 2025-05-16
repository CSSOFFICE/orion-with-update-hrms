<!-- action buttons -->
@include('misc.list-pages-actions')
<!-- action buttons -->

<!--stats panel-->
@if (auth()->user()->is_team)
    <div id="subtasks-stats-wrapper" class="stats-wrapper card-embed-fix">
        @if (@count($subtasks) > 0)
            @include('misc.list-pages-stats')
        @endif
    </div>
@endif
<!--stats panel-->

<!--tasks and kanban layouts-->
@if (auth()->user()->pref_view_tasks_layout == 'list')
    <div class="card-embed-fix  kanban-wrapper">
        @include('pages.subtasks.components.table.wrapper')
    </div>
@else
    <div class="card-embed-fix  kanban-wrapper">
        @include('pages.subtasks.components.table.wrapper')
    </div>
@endif
<!--/#tasks and kanban layouts-->

<!--filter-->
@if (auth()->user()->is_team)
    @include('pages.subtasks.components.misc.filter-tasks')
@endif
<!--filter-->

<!--task modal-->
@include('pages.task.modal')
