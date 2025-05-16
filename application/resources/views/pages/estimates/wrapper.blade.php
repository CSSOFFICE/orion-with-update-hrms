@extends('layout.wrapper') @section('content')
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

        <!--stats panel-->
        @if (auth()->user()->is_team)
            <div class="stats-wrapper" id="estimates-stats-wrapper">
                @include('misc.list-pages-stats')
            </div>
        @endif
        <!--stats panel-->

        <div class="row ">
            <div class="col-12">

                <!--tasks table-->
                @include('pages.estimates.components.table.wrapper')

                <!--tasks table-->
                <!--filter-->

                <!--filter-->
            </div>
        </div>


    </div>
    <script>
        $("#pref_view_quo_layout").on("click", function() {

            // window.location.reload(1);
        })
    </script>
    <!--main content -->
@endsection
