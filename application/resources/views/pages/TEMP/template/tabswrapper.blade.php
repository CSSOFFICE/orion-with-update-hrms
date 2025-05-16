<!-- action buttons -->
@include('misc.list-pages-actions')
<!-- action buttons -->

<!--stats panel-->
@if(auth()->user()->is_team)
<div id="demos-stats-wrapper" class="stats-wrapper card-embed-fix">
@if (@count($demos) > 0) @include('misc.list-pages-stats') @endif
</div>
@endif
<!--stats panel-->

<!--demos table-->
<div class="card-embed-fix">
@include('pages.demos.components.table.wrapper')
</div>
<!--demos table-->