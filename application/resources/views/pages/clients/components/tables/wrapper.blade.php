<!--main table view-->
@include('pages.clients.components.tables.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.clients.components.misc.filter-clients')
@endif
<!--filter-->