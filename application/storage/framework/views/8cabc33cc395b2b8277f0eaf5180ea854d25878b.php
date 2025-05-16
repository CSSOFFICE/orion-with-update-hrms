<!--checkbox actions-->
<?php echo $__env->make('pages.projects.components.actions.checkbox-actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!--main table view-->
<?php echo $__env->make('pages.projects.components.table.table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!--filter-->
<?php if(auth()->user()->is_team): ?>
<?php echo $__env->make('pages.projects.components.misc.filter-projects', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<!--filter-->
<script>
    $('.delete_project').on("click", function() {
        const csrfToken = "<?php echo e(csrf_token()); ?>";


        let id = $(this).data('id');
        let con = confirm("Delete WereHouse")
        if (con) {
            $.ajax({
                url: "<?php echo e(route('delete_project_w')); ?>",
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
                url: "<?php echo e(route('delete_project')); ?>",
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
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/projects/components/table/wrapper.blade.php ENDPATH**/ ?>