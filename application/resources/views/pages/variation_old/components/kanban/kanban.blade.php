<div class="boards count-{{ @count($tasks) }}" id="tasks-view-wrapper">
    <!--each board-->
    @foreach ($boards as $board)
        <!--board-->
        @include('pages.estimates.components.kanban.board')
    @endforeach
</div>
<!--ajax element-->
<span class="hidden" data-url=""></span>

<!--filter-->
<!--filter-->
