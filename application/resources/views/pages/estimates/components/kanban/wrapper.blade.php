<!--main table view-->
@include('pages.estimates.components.kanban.kanban')

<!--Update Card Poistion (team only)-->
@if(auth()->user()->is_team)
<span id="js-tasks-kanban-wrapper" class="hidden" data-position="{{ url('estimates/update-position') }}">placeholder</script>
@endif
