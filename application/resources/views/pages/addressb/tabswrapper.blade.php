<!-- action buttons -->
@include('misc.list-pages-actions')
<!-- action buttons -->

<!--stats panel-->
@if(auth()->user()->is_team)
<div id="contacts-stats-wrapper" class="stats-wrapper card-embed-fix">
@if (@count($addressb) > 0) @include('misc.list-pages-stats') @endif
</div>
@endif
<!--stats panel-->

<!--contacts table-->
<div class="card-embed-fix">
@include('pages.addressb.components.table.wrapper')
</div>
<!--contacts table-->
