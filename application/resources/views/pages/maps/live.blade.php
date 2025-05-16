@extends('layout.wrapper') @section('content')
    <script>
        function dummy() {}
        window.dummy = dummy;
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_Tb2yFfhKoOuPoViMsQFxELut8zPkKdM&callback=dummy">
    </script>
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCkUOdZ5y7hMm0yrcCQoCvLwzdM6M8s5qk&callback=dummy"> --}}
    </script>
    <script src="https://unpkg.com/@googlemaps/markerclustererplus/dist/index.min.js"></script>

    <!-- main content -->
    <div class="container-fluid">

        <!--page heading-->
        <div class="row page-titles">

            <!-- Page Title & Bread Crumbs -->
            @include('misc.heading-crumbs')
            <!--Page Title & Bread Crumbs -->


            <!-- action buttons -->
            @include('misc.list-pages-actions')
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
@endsection
