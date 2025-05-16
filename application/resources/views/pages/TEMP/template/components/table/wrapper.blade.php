<!--main table view-->
@include('pages.demos.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.demos.components.misc.filter-clients')
@endif
<!--filter-->