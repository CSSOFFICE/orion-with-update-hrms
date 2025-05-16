@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!-- action buttons -->
@include('misc.list-pages-actions')
<!-- action buttons -->

<!--heading-->
@include('pages.settings.sections.sources.table.table')
@endsection