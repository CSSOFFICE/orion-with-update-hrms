<?php

use Illuminate\Support\Facades\DB;

$deliveries = DB::table('delivery_weeks')->get();
?>

<div id="bill-form-container">
    @if (config('visibility.bill_mode') == 'viewing')
    <button class="btn btn-danger" id="PDFDownloadButton">PDF Download</button>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        /* Ensure the footer appears on every page */
    </style>

    <script type="text/javascript">
        document.getElementById('PDFDownloadButton').addEventListener('click', function() {
            document.querySelectorAll('#summery-table #profit_uniq').forEach(el => el.style.display = "none");
            document.querySelectorAll('#summery-table #nett_price_uniq').forEach(el => el.style.display = "none");
            var invoiceElement = document.getElementById('invoice-wrapper');
            invoiceElement.style.fontSize = '14px';

            var opt = {
                margin: [1, 0, 0.5, 0],
                filename: '{{ $bill->est_quotation_no }}' + '.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.90
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'portrait'
                },
            };

            html2pdf().from(invoiceElement).set(opt).save().then(function() {
                location.reload(); // Refresh the page after PDF download
            });
        });
    </script>

    @endif
    <div class="card card-body invoice-wrapper box-shadow" id="invoice-wrapper">

        <!--HEADER-->
        @if ($bill->bill_type == 'invoice')
        @include('pages.bill.components.elements.invoice.header-web')
        @endif
        @if ($bill->bill_type == 'estimate')
        @include('pages.bill.components.elements.estimate.header-web')
        @endif

        <hr style="margin: 5px 0;">
        <div class="row">
            <!--ADDRESSES-->

            <!--DATES & AMOUNT DUE-->
            @if ($bill->bill_type == 'invoice')
            <div class="col-12" id="invoice-dates-wrapper">
                @include('pages.bill.components.elements.invoice.dates')
                @include('pages.bill.components.elements.invoice.payments')
            </div>
            @endif
            @if ($bill->bill_type == 'estimate')
            <div class="col-12 " id="invoice-dates-wrapper">
                @include('pages.bill.components.elements.estimate.dates')
            </div>
            @endif


            <!--INVOICE TABLE-->
            @include('pages.bill.components.elements.main-table')


            <!--[EDITING] INVOICE LINE ITEMS BUTTONS -->
            @if (config('visibility.bill_mode') == 'editing')
            <div class="col-12">
                @include('pages.bill.components.misc.add-line-buttons')
            </div>
            @endif


            <!-- TOTAL & SUMMARY -->
            @include('pages.bill.components.elements.totals-table')



            <!--[VIEWING] INVOICE TERMS & MAKE PAYMENT BUTTON-->
            @if (config('visibility.bill_mode') == 'viewing')
            <div class="row term_by_rk" id='bill-terms'>
                <div class="col-12 m-4">
                    <!--exclution terms-->

                    {{-- <h4>Exclution</h4> --}}
                    <div id="invoice-terms">{!! clean($bill->bill_exclution) !!}</div>

                </div>


                <div class="col-lg-12 m-4">

                    <div id="delivery-terms" style="margin-left: 0px!important;"> <span>Delivery :
                            &nbsp;&nbsp;</span>{!! clean($bill->bill_delivery) !!}</div>
                </div>


            </div>
            <div class="col-12" id='bill-terms1'>
                <!--invoice terms-->
                <div class="text-left term_by_rk">
                    @if ($bill->bill_type == 'invoice')
                    <h4>{{ cleanLang(__('lang.invoice_terms')) }}</h4>
                    @else
                    <!-- <h4>{{ cleanLang(__('lang.estimate_terms')) }}</h4> -->
                    <h4>Terms & Conditions</h4>
                    @endif
                    <div id="invoice-terms">{!! clean($bill->bill_terms) !!}</div>
                </div>

                <div class="col-lg-12 pdf-footer" id="term_by_sm">
                    {{-- <h4 class="text-dark mt-4" style="font-weight:500">Your Faithfully</h4> --}}
                    <p></p>
                    <div class="col-lg-6" style="margin: 0 auto; text-align: center;">
                        <ul class="invoice-brand-img" style="bottom: 62px;">
                            <?php
                            $query = DB::table('xin_quo')->first();
                            $logoUrl = url('hrms/uploads/quo/' . $query->logo1);
                            $logoUrl2 = url('hrms/uploads/quo/' . $query->logo2);
                            $logoUrl3 = url('hrms/uploads/quo/' . $query->logo3);
                           
                            ?>
                            <li> <img src="<?php echo $logoUrl; ?>" alt="" width="70px"></li>
                            <li> <img src="<?php echo $logoUrl2; ?>" alt="" width="70px"></li>
                            <li> <img src="<?php echo $logoUrl3 ?? ''; ?>" alt="" width="70px"></li>
                            
                        </ul>
                    </div>
                </div>



                <!--client - make a payment button-->
                @if (auth()->user()->is_client)
                <hr>
                <div class="p-t-25 invoice-pay" id="invoice-buttons-container">
                    <div class="text-right">
                        <!--[invoice] download pdf-->
                        @if ($bill->bill_type == 'invoice')
                        <a class="btn btn-secondary btn-outline"
                            href="{{ url('/invoices/' . $bill->bill_invoiceid . '/pdf') }}" download>
                            <span><i class="mdi mdi-download"></i>
                                {{ cleanLang(__('lang.download')) }}</span> </a>
                        @else
                        <!--[estimate] download pdf-->
                        <a class="btn btn-secondary btn-outline"
                            href="{{ url('/estimates/' . $bill->bill_estimateid . '/pdf') }}" download>
                            <span><i class="mdi mdi-download"></i>
                                {{ cleanLang(__('lang.download')) }}</span> </a>
                        @endif
                        <!--[invoice] - make payment-->
                        @if ($bill->bill_type == 'invoice' && $bill->invoice_balance > 0)
                        <button class="btn btn-danger" id="invoice-make-payment-button">
                            {{ cleanLang(__('lang.make_a_payment')) }} </button>
                        @endif

                        <!--accept or decline-->
                        @if (in_array($bill->bill_status, ['new', 'revised']))
                        <!--decline-->
                        <button class="buttons-accept-decline btn btn-danger confirm-action-danger"
                            data-confirm-title="{{ cleanLang(__('lang.decline_estimate')) }}"
                            data-confirm-text="{{ cleanLang(__('lang.decline_estimate_confirm')) }}"
                            data-ajax-type="GET"
                            data-url="{{ url('/') }}/estimates/{{ $bill->bill_estimateid }}/decline">
                            {{ cleanLang(__('lang.decline_estimate')) }} </button>
                        <!--accept-->
                        <button class="buttons-accept-decline btn btn-success confirm-action-success"
                            data-confirm-title="{{ cleanLang(__('lang.accept_estimate')) }}"
                            data-confirm-text="{{ cleanLang(__('lang.accept_estimate_confirm')) }}"
                            data-ajax-type="GET"
                            data-url="{{ url('/') }}/estimates/{{ $bill->bill_estimateid }}/accept">
                            {{ cleanLang(__('lang.accept_estimate')) }} </button>
                        @endif


                    </div>
                    @endif

                </div>
                <!--payment buttons-->
                @include('pages.pay.buttons')
                @endif


                <!--[EDITING] INVOICE TERMS & MAKE PAYMENT BUTTON-->
                @if (config('visibility.bill_mode') == 'editing')
                <div class="col-12 mb-4">
                    <!--exclution terms-->
                    <div class="text-left term_by_rk">
                        {{-- <h4>Exclution</h4> --}}
                        <textarea class="form-control form-control-sm tinymce-textarea" rows="3" name="bill_exclution" id="exclution">{!! clean($bill->bill_exclution) !!}</textarea>
                    </div>
                </div>

                <div class="col-12 mb-4">
                    <!--exclution terms-->
                    <div class="delivery-date mb-4 ">
                        <label for="" class="m-4">Delivery</label>
                        <select name="bill_delivery" id="bill_delivery">
                            <option value="" disabled>Select Week</option>
                            @foreach ($deliveries as $item)
                            <option value="{{ $item->delivery_time }}" <?php echo $item->delivery_time == $bill->bill_delivery ? 'selected' : ''; ?>>
                                {{ $item->delivery_time }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <!--invoice terms-->
                    <div class="text-left term_by_rk">
                        @if ($bill->bill_type == 'invoice')
                        <h4>{{ cleanLang(__('lang.invoice_terms')) }}</h4>
                        @else
                        <!-- <h4>{{ cleanLang(__('lang.estimate_terms')) }}</h4> -->
                        <h4>Terms & Conditions</h4>
                        @endif
                        <textarea class="form-control form-control-sm tinymce-textarea" rows="3" name="bill_terms" id="bill_terms">{!! clean($bill->bill_terms) !!}</textarea>
                    </div>
                    <!--client - make a payment button-->
                    <div class="text-right p-t-25">
                        <!--tax rates-->
                        <button class="btn btn-info btn-sm js-elements-popover-button" tabindex="0"
                            id="billing-tax-popover-button" data-placement="top"
                            data-popover-content="{{ $elements['tax_popover'] }}"
                            data-title="{{ cleanLang(__('lang.tax_rates')) }}">
                            {{ cleanLang(__('lang.tax_rates')) }}
                        </button>
                        <!--discounts-->
                        <button class="btn btn-success btn-sm js-elements-popover-button" tabindex="0"
                            id="billing-discounts-popover-button" data-placement="top"
                            data-title="{{ cleanLang(__('lang.discount')) }}"
                            data-popover-content="{{ $elements['discount_popover'] }}">
                            {{ cleanLang(__('lang.discounts')) }}
                        </button>
                        @if ($bill->bill_type == 'invoice')
                        <!--cancel-->
                        <a class="btn btn-secondary btn-sm"
                            href="{{ url('/invoices/' . $bill->bill_invoiceid) }}">Cancel</a>
                        <!--save changes-->
                        <button class="btn btn-danger btn-sm"
                            data-url="{{ url('/invoices/' . $bill->bill_invoiceid . '/edit-invoice') }}"
                            data-type="form" data-form-id="bill-form-container" data-ajax-type="post"
                            id="billing-save-button">
                            {{ cleanLang(__('lang.save_changes')) }}
                        </button>
                        @else
                        <a class="btn btn-secondary btn-sm"
                            href="{{ url('/estimates/' . $bill->bill_estimateid) }}">{{ cleanLang(__('lang.cancel')) }}</a>



                        <!--save changes-->
                        {{-- <button type="submit" class="btn btn-danger btn-sm"
                                data-url="{{ url('/estimates/' . $bill->bill_estimateid . '/edit-estimate') }}"
                        data-type="form" data-form-id="bill-form-container" data-ajax-type="post"
                        id="billing-save-button">
                        {{ cleanLang(__('lang.save_changes')) }}
                        </button> --}}

                        <button class="btn btn-success btn-sm" id="quo_submit" type="button">Submit</button>
                        @endif
                    </div>
                </div>
                @endif

            </div>
        </div>

        <!--ADMIN ONLY NOTES-->
        {{-- @if (auth()->user()->is_team)
        @if (config('visibility.bill_mode') == 'viewing')
            <div class="card card-body invoice-wrapper box-shadow" id="invoice-wrapper">
                <h4 class="">{{ cleanLang(__('lang.notes')) }} <span
            class="align-middle text-themecontrast font-16" data-toggle="tooltip"
            title="{{ cleanLang(__('lang.not_visisble_to_client')) }}" data-placement="top"><i
                class="ti-info-alt"></i></span></h4>
        <div>{!! clean($bill->bill_notes) !!}</div>
    </div>
    @endif
    @if (config('visibility.bill_mode') == 'editing')
    <div class="card card-body invoice-wrapper box-shadow" id="invoice-wrapper">
        <h4 class="">{{ cleanLang(__('lang.notes')) }} <span
                class="align-middle text-themecontrast font-16" data-toggle="tooltip"
                title="{{ cleanLang(__('lang.not_visisble_to_client')) }}" data-placement="top"><i
                    class="ti-info-alt"></i></span></h4>
        <div>
            <textarea class="form-control form-control-sm tinymce-textarea" rows="3" name="bill_notes" id="bill_notes">{!! clean($bill->bill_notes) !!}</textarea>
        </div>
    </div>
    @endif
    @endif --}}

    <!--INVOICE LOGIC-->
    @if (config('visibility.bill_mode') == 'editing')
    @include('pages.bill.components.elements.logic')
    @endif

</div>

<!--ELEMENTS (invoice line item)-->
@if (config('visibility.bill_mode') == 'editing')
<table class="hidden" id="billing-line-template-plain">
    @include('pages.bill.components.elements.line-plain')
</table>
<table class="hidden" id="billing-line-template-time">
    @include('pages.bill.components.elements.line-time')
</table>
<table class="hidden" id="billing-line-template-product">
    @include('pages.bill.components.elements.line-product')
</table>

<!--MODALS-->
@include('pages.bill.components.modals.items')
@include('pages.bill.components.modals.expenses')
@include('pages.bill.components.timebilling.modal')

<!--[DYNAMIC INLINE SCRIPT] - Get lavarel objects and convert to javascript onject-->
<script>
    $(document).ready(function() {
        NXINVOICE.DATA.INVOICE = $.parseJSON('{!! $bill->json !!}');
        NXINVOICE.DOM.domState();
    });
</script>
@endif

@if ($bill->bill_type == 'estimate')
<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js">
</script> -->

<!-- <script type="text/javascript">
        $("body").on("click", "#estimateDownloadButton", function() {
            let n = $("#qt_no").text();
            let name = "Estimate" + n;
            html2canvas($('#bill-form-container')[0], {

                onrendered: function(canvas) {
                    // var extra_canvas = document.createElement("canvas");
                    // extra_canvas.setAttribute('width',750);
                    //     extra_canvas.setAttribute('height',700);
                    //     var ctx = extra_canvas.getContext('2d');
                    //     ctx.drawImage(canvas,0,0,canvas.width, canvas.height,0,0,750,700);
                    var data = canvas.toDataURL();
                    var docDefinition = {
                        content: [{
                            alignment: 'center',
                            image: data,
                            width: 700,
                            height: 700,
                            //   fontSize: 23,
                            margin: [0, 25],
                            dpi: 72
                            //   dpi: 192, letterRendering: true, width: 600, height: 500
                            //   margin: [0, 20, 0, 0],
                            //     alignment: 'justify'
                        }]
                    };
                    pdfMake.createPdf(docDefinition).download(name + ".pdf");
                }
            });
        });
    </script> -->

<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script> -->
<script type="text/javascript" src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>

<script type="text/javascript">
    $("body").on("click", "#estimateDownloadButton", function() {

        var content = $("#bill-form-container")[0];
        var n = "{{ $bill->est_quotation_no }}";


        var name = "Estimate" + n;

        var options = {
            margin: 0,
            filename: name,
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 1.1
            },
            jsPDF: {
                unit: 'in',
                format: 'a4',
                orientation: 'portrait'
            }
        };
        html2pdf(content, options);

    });
</script>
<script>
    $('#quo_submit').on("click", function() {
        $.ajax({
            type: "GET",
            url: "{{ route('waitingforappoval') }}",
            data: {
                id: "{{ $bill->bill_estimateid }}"
            },
            success: function(data) {
                alert("Data Save and Waiting for Management to Approve");
                window.location.href = "{{ url('/estimates/' . $bill->bill_estimateid) }}";
            },
        });
    });
</script>
@endif
