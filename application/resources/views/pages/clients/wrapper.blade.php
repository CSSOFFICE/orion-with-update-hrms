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

    <!-- page content -->
    <div class="row">
        <div class="col-12" id="indv" style="{{ $cust_type == 1 ? 'display:block;' : 'display:none;' }}">
            <!--clients table-->
            @if($cust_type==1)

            @include('pages.clients.components.table.wrapper')
            @endif
            <!--clients table-->
        </div>

        <div class="col-12" id="com" style="{{ $cust_type == 2 ? 'display:block;' : 'display:none;' }}">
            <!--clients table-->
            @if($cust_type==2)
            @include('pages.clients.components.tables.wrapper')
            @endif

            <!--clients table-->
        </div>
    </div>
    <!--page content -->


</div>
<!--main content -->
<script>
    function abc(e) {

        if (e == 1) {
            $('#indv').css('display', 'block');
            $('#com').css('display', 'none');
        } else {
            $('#com').css('display', 'block');
            $('#indv').css('display', 'none');
        }
    }
</script>
@endsection
