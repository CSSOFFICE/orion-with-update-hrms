<!-- action buttons -->
@include('misc.list-pages-actions')
<!-- action buttons -->

<!--stats panel-->
@if(auth()->user()->is_team)
<div id="timesheets-stats-wrapper" class="stats-wrapper card-embed-fix">
@if (@count($timesheets) > 0) @include('misc.list-pages-stats') @endif
</div>
@endif
<!--stats panel-->

<!--timesheets table-->
<div class="card-embed-fix">
@include('pages.timecard.components.table.wrapper')
</div>
<!--timesheets table-->
