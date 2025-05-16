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
    @if(auth()->user()->is_team)
    <div id="stats-wrapper demos-stats-wrapper">
    @include('misc.list-pages-stats')
    </div>
    @endif
    <!--stats panel-->


    <!-- page content -->
    <div class="row">
        <div class="col-12" id="demos-table-wrapper">
            <!--demos table-->
            @include('pages.demos.components.table.wrapper')
            <!--demos table-->
        </div>
    </div>
    <!--page content -->

    <!--filter-->
    @include('pages.demos.components.misc.filter-demos')
    <!--filter-->
</div>
<!--main content -->
@endsection