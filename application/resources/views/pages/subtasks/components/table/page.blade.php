@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!-- action buttons -->
@include('misc.list-pages-actions')
<!-- action buttons -->

<!--heading-->
@include('pages.settings.sections.subtasks.table.table')

<!--section js resource-->
@endsection