<!-- action buttons -->
@include('misc.list-pages-actions')
<!-- action buttons -->

<!--stats panel-->
@if(auth()->user()->is_team)
<div id="invoices-stats-wrapper" class="stats-wrapper card-embed-fix">
@if (@count($invoice) > 0) @include('misc.list-pages-stats') @endif
</div>
@endif
<!--stats panel-->

<!--expenses table-->
<div class="card-embed-fix">
@include('pages.reports.expense.components.wrapper')
</div>
<!--expenses table-->