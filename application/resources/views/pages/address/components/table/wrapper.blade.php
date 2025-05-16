<!--bulk actions-->
@include('pages.address.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.address.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.address.components.misc.filter-contacts')
@endif
<!--filter-->
