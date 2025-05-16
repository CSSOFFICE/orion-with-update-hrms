@if(isset($page['page']) && $page['page'] == 'estimates')
@include('pages.variation.components.actions.checkbox-actions')
@endif

<!--main table view-->
@include('pages.variation.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.variation.components.misc.filter-estimates')
@endif
<!--filter-->
