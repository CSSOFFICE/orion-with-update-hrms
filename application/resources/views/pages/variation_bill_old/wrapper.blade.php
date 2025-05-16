@extends('layout.wrapper') @section('content')
<style>
    .selected-option {
        background-color: #6c757d !important;
        color: white !important;
    }
</style>
<!-- jQuery (Latest version) -->

<!-- main content -->
<div class="container-fluid {{ $page['mode'] ?? '' }}" id="invoice-container">

    <!--HEADER SECTION-->
    <div class="row page-titles">

        <!--BREAD CRUMBS & TITLE-->
        <div class="col-md-12 col-lg-7 align-self-center {{ $page['crumbs_special_class'] ?? '' }}" id="breadcrumbs">
            <!--attached to project-->
            <a id="InvoiceTitleAttached"
                class="{{ runtimeInvoiceAttachedProject('project-title', $bill->bill_projectid) }}"
                href="{{ url('projects/' . $bill->bill_projectid) }}">
                <h3 class="text-themecolor" id="InvoiceTitleProject">{{ $page['heading'] ?? '' }}</h3>
            </a>
            <!--not attached to project-->
            <h4 id="InvoiceTitleNotAttached"
                class="muted {{ runtimeInvoiceAttachedProject('alternative-title', $bill->bill_projectid) }}">
                {{ cleanLang(__('lang.not_attached_to_project')) }}
            </h4>
            <!--crumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">{{ cleanLang(__('lang.app')) }}</li>
                @if (isset($page['crumbs']))
                @foreach ($page['crumbs'] as $title)
                <li class="breadcrumb-item @if ($loop->last) active active-bread-crumb @endif">
                    {{ $title ?? '' }}
                </li>
                @endforeach
                @endif
            </ol>
            <!--crumbs-->
        </div>

        <!--ACTIONS-->
        @if ($bill->bill_type == 'invoice')
        @include('pages.variation_bill.components.misc.invoice.actions')
        @endif
        @if ($bill->bill_type == 'estimate')
        @include('pages.variation_bill.components.misc.estimate.actions')
        @endif

    </div>
    <!--/#HEADER SECTION-->
    <!-- BILL CONTENT -->
    @php
    $options = explode(',', $bill->quotation_options);
    @endphp

    {{-- If "summary" is not in the options, add it manually --}}
    <!-- Input Field -->


    @if (!in_array('summary', $options))
    <button class="btn btn-primary m-r-10 option-button selected-option" data-template="summary">
        {{ strtoupper('summary') }}
    </button>
    @endif

    {{-- Loop through all options --}}
    @foreach ($options as $k=>$option)
    @if (!empty($option))
    @if ($option === 'summary')
    <button class="btn btn-primary m-r-10 option-button selected-option" data-template="{{ $option }}">
        {{ strtoupper($option) }}
    </button>
    @else
    <button class="btn btn-primary  m-r-10 option-button" data-template="{{ $option }}" data-id="{{ $k+1 }}">
        @if ($option == 'preliminaries')
        {{ strtoupper('preliminaries') }}
        @elseif ($option == 'insurance')
        {{ strtoupper('insurance') }}
        @elseif ($option == 'schedule_of_works')
        {{ strtoupper('schedule of works') }}
        @elseif($option == 'plumbing_sanity')
        {{ strtoupper('plumbing & sanity') }}
        @elseif($option == 'elec_acme')
        {{ strtoupper('elec & acme') }}
        @elseif($option == 'external_works')
        {{ strtoupper('external works') }}
        @elseif($option == 'pc_ps_sums')
        {{ strtoupper('pc & ps sums') }}
        @elseif($option == 'others')
        {{ strtoupper('others') }}
        @else
        {{ strtoupper($option) }}
        @endif
    </button>
    @endif
    @endif
    @endforeach



    <!--BILL CONTENT-->
    <div class="row">
        <div class="col-md-12 p-t-30">
            @include('pages.variation_bill.bill-web')
        </div>
    </div>
</div>
<!--main content -->

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var buttons = document.querySelectorAll('.option-button');

        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Remove the 'selected-option' class from all buttons
                buttons.forEach(function(btn) {
                    btn.classList.remove('selected-option');
                });
                // Add the 'selected-option' class to the clicked button
                this.classList.add('selected-option');
            });
        });
    });
</script>
