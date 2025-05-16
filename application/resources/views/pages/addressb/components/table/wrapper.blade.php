<!--bulk actions-->
@include('pages.addressb.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.addressb.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.addressb.components.misc.filter-contacts')
@endif
<!--filter-->
