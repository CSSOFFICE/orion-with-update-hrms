 <?php $__env->startSection('content'); ?>
    <script>
        function dummy() {}
        window.dummy = dummy;
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_Tb2yFfhKoOuPoViMsQFxELut8zPkKdM&callback=dummy">
    </script>
    
    </script>
    <script src="https://unpkg.com/@googlemaps/markerclustererplus/dist/index.min.js"></script>

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
            <div class="col-12">

                <div class="col-lg-8  col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <!--The div element for the map -->
                            <div id="map" style="height: 400px"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--page content -->




    </div>
    <!--main content -->

    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.wrapper', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/maps/live.blade.php ENDPATH**/ ?>