 <?php $__env->startSection('content'); ?>
<!-- main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">

        <!-- Page Title & Bread Crumbs -->
        <?php echo $__env->make('misc.heading-crumbs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!--Page Title & Bread Crumbs -->


        <!-- action buttons -->
        <?php echo $__env->make('misc.list-pages-actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- action buttons -->

    </div>
    <!--page heading-->

    <!-- page content -->
    <div class="row">
        <div class="col-12" id="indv">
            <!--clients table-->
            <?php echo $__env->make('pages.clients.components.table.wrapper', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <!--clients table-->
        </div>

        <div class="col-12" id="com" style="display:none;">
            <!--clients table-->
            <?php echo $__env->make('pages.clients.components.tables.wrapper', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <!--clients table-->
        </div>
    </div>
    <!--page content -->


</div>
<!--main content -->
<script>
    function abc(e){
        // alert('hi');
        if(e==1){
            $('#indv').css('display','block');
            $('#com').css('display','none');
        }else{
            $('#com').css('display','block');
            $('#indv').css('display','none');
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.wrapper', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/clients/wrapper.blade.php ENDPATH**/ ?>