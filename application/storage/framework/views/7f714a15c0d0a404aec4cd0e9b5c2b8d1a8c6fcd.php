<?php

use Illuminate\Support\Facades\DB;

$deliveries = DB::table('delivery_weeks')->get();
?>

<div id="bill-form-container">
    <?php if(config('visibility.bill_mode') == 'viewing'): ?>
    <button class="btn btn-danger" id="PDFDownloadButton">PDF Download</button>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        /* Ensure the footer appears on every page */
    </style>

    <script type="text/javascript">
        document.getElementById('PDFDownloadButton').addEventListener('click', function() {
            document.getElementById('profit_uniq').style.display = "none";
            document.getElementById('nett_price_uniq').style.display = "none";
            var invoiceElement = document.getElementById('invoice-wrapper');
            invoiceElement.style.fontSize = '14px';

            var opt = {
                margin: [1, 0, 0.5, 0],
                filename: '<?php echo e($bill->est_quotation_no); ?>' + '.pdf',
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

    <?php endif; ?>
    <div class="card card-body invoice-wrapper box-shadow" id="invoice-wrapper">

        <!--HEADER-->
        <?php if($bill->bill_type == 'invoice'): ?>
        <?php echo $__env->make('pages.bill.components.elements.invoice.header-web', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if($bill->bill_type == 'estimate'): ?>
        <?php echo $__env->make('pages.bill.components.elements.estimate.header-web', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

        <hr style="margin: 5px 0;">
        <div class="row">
            <!--ADDRESSES-->

            <!--DATES & AMOUNT DUE-->
            <?php if($bill->bill_type == 'invoice'): ?>
            <div class="col-12" id="invoice-dates-wrapper">
                <?php echo $__env->make('pages.bill.components.elements.invoice.dates', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo $__env->make('pages.bill.components.elements.invoice.payments', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <?php endif; ?>
            <?php if($bill->bill_type == 'estimate'): ?>
            <div class="col-12 " id="invoice-dates-wrapper">
                <?php echo $__env->make('pages.bill.components.elements.estimate.dates', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <?php endif; ?>


            <!--INVOICE TABLE-->
            <?php echo $__env->make('pages.bill.components.elements.main-table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


            <!--[EDITING] INVOICE LINE ITEMS BUTTONS -->
            <?php if(config('visibility.bill_mode') == 'editing'): ?>
            <div class="col-12">
                <?php echo $__env->make('pages.bill.components.misc.add-line-buttons', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <?php endif; ?>


            <!-- TOTAL & SUMMARY -->
            <?php echo $__env->make('pages.bill.components.elements.totals-table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



            <!--[VIEWING] INVOICE TERMS & MAKE PAYMENT BUTTON-->
            <?php if(config('visibility.bill_mode') == 'viewing'): ?>
            <div class="row term_by_rk" id='bill-terms'>
                <div class="col-12 m-4">
                    <!--exclution terms-->

                    
                    <div id="invoice-terms"><?php echo clean($bill->bill_exclution); ?></div>

                </div>


                <div class="col-lg-12 m-4">

                    <div id="delivery-terms" style="margin-left: 0px!important;"> <span>Delivery :
                            &nbsp;&nbsp;</span><?php echo clean($bill->bill_delivery); ?></div>
                </div>


            </div>
            <div class="col-12" id='bill-terms1'>
                <!--invoice terms-->
                <div class="text-left term_by_rk">
                    <?php if($bill->bill_type == 'invoice'): ?>
                    <h4><?php echo e(cleanLang(__('lang.invoice_terms'))); ?></h4>
                    <?php else: ?>
                    <!-- <h4><?php echo e(cleanLang(__('lang.estimate_terms'))); ?></h4> -->
                    <h4>Terms & Conditions</h4>
                    <?php endif; ?>
                    <div id="invoice-terms"><?php echo clean($bill->bill_terms); ?></div>
                </div>

                <div class="col-lg-12 pdf-footer" id="term_by_sm">
                    
                    <p></p>
                    <div class="col-lg-6" style="margin: 0 auto; text-align: center;">
                        <ul class="invoice-brand-img" style="bottom: 62px;">
                            <?php
                            $query = DB::table('xin_quo')->first();
                            $logoUrl = url('hrms/uploads/quo/' . $query->logo1);
                            $logoUrl2 = url('hrms/uploads/quo/' . $query->logo2);
                            $logoUrl3 = url('hrms/uploads/quo/' . $query->logo3);
                            $logoUrl4 = url('hrms/uploads/quo/' . $query->logo4);
                            ?>
                            <li> <img src="<?php echo $logoUrl; ?>" alt="" width="70px"></li>
                            <li> <img src="<?php echo $logoUrl2; ?>" alt="" width="70px"></li>
                            <li> <img src="<?php echo $logoUrl3 ?? ''; ?>" alt="" width="70px"></li>
                            <li> <img src="<?php echo $logoUrl4 ?? ''; ?>" alt="" width="70px"></li>
                        </ul>
                    </div>
                </div>



                <!--client - make a payment button-->
                <?php if(auth()->user()->is_client): ?>
                <hr>
                <div class="p-t-25 invoice-pay" id="invoice-buttons-container">
                    <div class="text-right">
                        <!--[invoice] download pdf-->
                        <?php if($bill->bill_type == 'invoice'): ?>
                        <a class="btn btn-secondary btn-outline"
                            href="<?php echo e(url('/invoices/' . $bill->bill_invoiceid . '/pdf')); ?>" download>
                            <span><i class="mdi mdi-download"></i>
                                <?php echo e(cleanLang(__('lang.download'))); ?></span> </a>
                        <?php else: ?>
                        <!--[estimate] download pdf-->
                        <a class="btn btn-secondary btn-outline"
                            href="<?php echo e(url('/estimates/' . $bill->bill_estimateid . '/pdf')); ?>" download>
                            <span><i class="mdi mdi-download"></i>
                                <?php echo e(cleanLang(__('lang.download'))); ?></span> </a>
                        <?php endif; ?>
                        <!--[invoice] - make payment-->
                        <?php if($bill->bill_type == 'invoice' && $bill->invoice_balance > 0): ?>
                        <button class="btn btn-danger" id="invoice-make-payment-button">
                            <?php echo e(cleanLang(__('lang.make_a_payment'))); ?> </button>
                        <?php endif; ?>

                        <!--accept or decline-->
                        <?php if(in_array($bill->bill_status, ['new', 'revised'])): ?>
                        <!--decline-->
                        <button class="buttons-accept-decline btn btn-danger confirm-action-danger"
                            data-confirm-title="<?php echo e(cleanLang(__('lang.decline_estimate'))); ?>"
                            data-confirm-text="<?php echo e(cleanLang(__('lang.decline_estimate_confirm'))); ?>"
                            data-ajax-type="GET"
                            data-url="<?php echo e(url('/')); ?>/estimates/<?php echo e($bill->bill_estimateid); ?>/decline">
                            <?php echo e(cleanLang(__('lang.decline_estimate'))); ?> </button>
                        <!--accept-->
                        <button class="buttons-accept-decline btn btn-success confirm-action-success"
                            data-confirm-title="<?php echo e(cleanLang(__('lang.accept_estimate'))); ?>"
                            data-confirm-text="<?php echo e(cleanLang(__('lang.accept_estimate_confirm'))); ?>"
                            data-ajax-type="GET"
                            data-url="<?php echo e(url('/')); ?>/estimates/<?php echo e($bill->bill_estimateid); ?>/accept">
                            <?php echo e(cleanLang(__('lang.accept_estimate'))); ?> </button>
                        <?php endif; ?>


                    </div>
                    <?php endif; ?>

                </div>
                <!--payment buttons-->
                <?php echo $__env->make('pages.pay.buttons', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>


                <!--[EDITING] INVOICE TERMS & MAKE PAYMENT BUTTON-->
                <?php if(config('visibility.bill_mode') == 'editing'): ?>
                <div class="col-12 mb-4">
                    <!--exclution terms-->
                    <div class="text-left term_by_rk">
                        
                        <textarea class="form-control form-control-sm tinymce-textarea" rows="3" name="bill_exclution" id="exclution"><?php echo clean($bill->bill_exclution); ?></textarea>
                    </div>
                </div>

                <div class="col-12 mb-4">
                    <!--exclution terms-->
                    <div class="delivery-date mb-4 ">
                        <label for="" class="m-4">Delivery</label>
                        <select name="bill_delivery" id="bill_delivery">
                            <option value="" disabled>Select Week</option>
                            <?php $__currentLoopData = $deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->delivery_time); ?>" <?php echo $item->delivery_time == $bill->bill_delivery ? 'selected' : ''; ?>>
                                <?php echo e($item->delivery_time); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <!--invoice terms-->
                    <div class="text-left term_by_rk">
                        <?php if($bill->bill_type == 'invoice'): ?>
                        <h4><?php echo e(cleanLang(__('lang.invoice_terms'))); ?></h4>
                        <?php else: ?>
                        <!-- <h4><?php echo e(cleanLang(__('lang.estimate_terms'))); ?></h4> -->
                        <h4>Terms & Conditions</h4>
                        <?php endif; ?>
                        <textarea class="form-control form-control-sm tinymce-textarea" rows="3" name="bill_terms" id="bill_terms"><?php echo clean($bill->bill_terms); ?></textarea>
                    </div>
                    <!--client - make a payment button-->
                    <div class="text-right p-t-25">
                        <!--tax rates-->
                        <button class="btn btn-info btn-sm js-elements-popover-button" tabindex="0"
                            id="billing-tax-popover-button" data-placement="top"
                            data-popover-content="<?php echo e($elements['tax_popover']); ?>"
                            data-title="<?php echo e(cleanLang(__('lang.tax_rates'))); ?>">
                            <?php echo e(cleanLang(__('lang.tax_rates'))); ?>

                        </button>
                        <!--discounts-->
                        <button class="btn btn-success btn-sm js-elements-popover-button" tabindex="0"
                            id="billing-discounts-popover-button" data-placement="top"
                            data-title="<?php echo e(cleanLang(__('lang.discount'))); ?>"
                            data-popover-content="<?php echo e($elements['discount_popover']); ?>">
                            <?php echo e(cleanLang(__('lang.discounts'))); ?>

                        </button>
                        <?php if($bill->bill_type == 'invoice'): ?>
                        <!--cancel-->
                        <a class="btn btn-secondary btn-sm"
                            href="<?php echo e(url('/invoices/' . $bill->bill_invoiceid)); ?>">Cancel</a>
                        <!--save changes-->
                        <button class="btn btn-danger btn-sm"
                            data-url="<?php echo e(url('/invoices/' . $bill->bill_invoiceid . '/edit-invoice')); ?>"
                            data-type="form" data-form-id="bill-form-container" data-ajax-type="post"
                            id="billing-save-button">
                            <?php echo e(cleanLang(__('lang.save_changes'))); ?>

                        </button>
                        <?php else: ?>
                        <a class="btn btn-secondary btn-sm"
                            href="<?php echo e(url('/estimates/' . $bill->bill_estimateid)); ?>"><?php echo e(cleanLang(__('lang.cancel'))); ?></a>



                        <!--save changes-->
                        

                        <button class="btn btn-success btn-sm" id="quo_submit" type="button">Submit</button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <!--ADMIN ONLY NOTES-->
        

    <!--INVOICE LOGIC-->
    <?php if(config('visibility.bill_mode') == 'editing'): ?>
    <?php echo $__env->make('pages.bill.components.elements.logic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

</div>

<!--ELEMENTS (invoice line item)-->
<?php if(config('visibility.bill_mode') == 'editing'): ?>
<table class="hidden" id="billing-line-template-plain">
    <?php echo $__env->make('pages.bill.components.elements.line-plain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</table>
<table class="hidden" id="billing-line-template-time">
    <?php echo $__env->make('pages.bill.components.elements.line-time', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</table>
<table class="hidden" id="billing-line-template-product">
    <?php echo $__env->make('pages.bill.components.elements.line-product', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</table>

<!--MODALS-->
<?php echo $__env->make('pages.bill.components.modals.items', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('pages.bill.components.modals.expenses', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('pages.bill.components.timebilling.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!--[DYNAMIC INLINE SCRIPT] - Get lavarel objects and convert to javascript onject-->
<script>
    $(document).ready(function() {
        NXINVOICE.DATA.INVOICE = $.parseJSON('<?php echo $bill->json; ?>');
        NXINVOICE.DOM.domState();
    });
</script>
<?php endif; ?>

<?php if($bill->bill_type == 'estimate'): ?>
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
        var n = "<?php echo e($bill->est_quotation_no); ?>";


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
            url: "<?php echo e(route('waitingforappoval')); ?>",
            data: {
                id: "<?php echo e($bill->bill_estimateid); ?>"
            },
            success: function(data) {
                alert("Data Save and Waiting for Management to Approve");
                window.location.href = "<?php echo e(url('/estimates/' . $bill->bill_estimateid)); ?>";
            },
        });
    });
</script>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/bill/bill-web.blade.php ENDPATH**/ ?>