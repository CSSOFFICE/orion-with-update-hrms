<!--checkbox actions-->
@include('pages.projects.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.projects.components.table.table')
<!--filter-->
@if(auth()->user()->is_team)
@include('pages.projects.components.misc.filter-projects')
@endif
<!--filter-->
<script>
    $('.delete_project').on("click", function() {
        const csrfToken = "{{ csrf_token() }}";


        let id = $(this).data('id');
        let con = confirm("Delete WereHouse")
        if (con) {
            $.ajax({
                url: "{{route('delete_project_w')}}",
                type: "post",
                data: {
                    project_id: id,
                    _token: csrfToken
                },
                success: function(re) {

                }
            })
        } else {
            $.ajax({
                url: "{{route('delete_project')}}",
                type: "post",
                data: {
                    project_id: id,
                    _token: csrfToken
                },
                success: function(re) {

                }
            })
        }
    })
</script>
