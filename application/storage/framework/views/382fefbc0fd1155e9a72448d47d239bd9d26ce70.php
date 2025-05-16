        <!--HEADER-->

        <style>
            /* .card-body {
                padding-left: 25px;
                padding-right: 25px;
                width: 1200px;
                margin: auto;

            }

            #content-container {
                overflow-x: scroll;
            } */

            .wrap-text {
                white-space: normal;
                /* Allows the text to break into multiple lines */
                word-wrap: break-word;
                /* Break long words to fit in the cell */
            }

            .card-body table tbody {
                overflow-x: scroll;
            }

            .invoice-wrapper {
                background-color: white;
            }
        </style>
        <?php
        $billing = DB::table('billing_addresses')
        ->where('client_id', $bill->client_id)
        ->where('d_address', 1)
        ->first();
        ?>
        <div class="row " id="quote_header">

            <table style="font-family: Arial, sans-serif;align-item:center;width:100%">

                <tr>
                    <td style="vertical-align: top;">
                        <?php
                        $query = DB::table('xin_quo')->first();
                        $logoUrl7 = url('hrms/uploads/quo/' . $query->logo7);                       
                        ?>                        
                        <img src="<?php echo $logoUrl7; ?>" alt="Invoice logo" height="150px"></td>

                    
                    <td style="vertical-align: top;text-align:right;" width="50%" class="pr-5">
                        <h5 style="font-weight:bold;">ORION INTEGRATED SERVICES PTE LTD</h5><br>
                        1 YISHUN INDUSTRIAL STREET 1<br>
                        #08-15 A'POSH BIZHUB<br>
                        SINGAPORE 768160<br><br>
                        TEL: 6734 0032&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FAX: 6734 0728<br>
                        E-MAIL: accounts@ois.com.sg<br>
                        GST REG NO.: 200809511K<br>
                        CO REG NO.: 200809511K
                    </td>


                </tr>
                <tr>
                    <td style="vertical-align: top;" width="50%" class="pl-5">
                        <h5 style="font-weight:bold;">
                            <?php echo e($bill->cust_type == 1 ? strtoupper($bill->f_name) : strtoupper($bill->client_company_name)); ?>

                        </h5><br>
                        Client Email: <?php echo e($bill->u_email ?? ''); ?><br>
                        Client Phone:<?php echo e($bill->client_phone ?? ''); ?><br><br>
                        Billing Address: <?php echo e($billing->street ?? ''); ?><br>
                        Person InCharge: <?php echo e($billing->p_i ?? ''); ?><br>
                        Email: <?php echo e($billing->p_email ?? ''); ?><br>
                        Contact: <?php echo e($billing->p_contact ?? ''); ?>

                    </td>
                    
                    
                </tr>

            </table>

            <span class="mt-5 pl-5 pull-left">
                <h5><b>Quotation No: <?php echo e($bill->est_quotation_no); ?></b>
                </h5>
                <h5>
                    <?php if(config('visibility.bill_mode') == 'viewing'): ?>
                    <b>Quotation Title: <?php echo e($bill->q_title); ?></b>
                    <?php elseif(config('visibility.bill_mode') == 'editing'): ?>
                    <input type="text" class="form-control form-control-sm" id="q_title" name="q_title"
                        value="<?php echo e($bill->q_title); ?>" placeholder="Quotation Title">
                    <?php endif; ?>
                </h5>
                <?php if(config('visibility.bill_mode') == 'editing'): ?>
                <textarea class="form-control form-control-sm tinymce-textarea" rows="3" name="subb" id="exclutiony_subb"><?php echo clean($bill->subb??''); ?></textarea>
                <?php else: ?>
                <h6 class="pt-2"><?php echo clean($bill->subb); ?></h6>
                <?php endif; ?>
            </span>

        </div>

        <div>

            <!--status-->
            <span class="pull-right d-none">
                <!--draft-->
                <span class="js-estimate-statuses <?php echo e(runtimeEstimateStatus('draft', $bill->bill_status)); ?>"
                    id="estimate-status-draft">
                    <h1 class="text-uppercase <?php echo e(runtimeEstimateStatusColors('draft', 'text')); ?> muted">
                        <?php echo e(cleanLang(__('lang.draft'))); ?>

                    </h1>
                </span>
                <!--new-->
                <span class="js-estimate-statuses <?php echo e(runtimeEstimateStatus('new', $bill->bill_status)); ?>"
                    id="estimate-status-new">
                    <h1 class="text-uppercase <?php echo e(runtimeEstimateStatusColors('new', 'text')); ?>">
                        <?php echo e(cleanLang(__('lang.new'))); ?>

                    </h1>
                </span>
                <!--accepted-->
                <span class="js-estimate-statuses <?php echo e(runtimeEstimateStatus('accepted', $bill->bill_status)); ?>"
                    id="estimate-status-accpeted">
                    <h1 class="text-uppercase <?php echo e(runtimeEstimateStatusColors('accepted', 'text')); ?>">
                        <?php echo e(cleanLang(__('lang.accepted'))); ?>

                    </h1>
                </span>
                <!--declined-->
                <span class="js-estimate-statuses <?php echo e(runtimeEstimateStatus('declined', $bill->bill_status)); ?>"
                    id="estimate-status-declined">
                    <h1 class="text-uppercase <?php echo e(runtimeEstimateStatusColors('declined', 'text')); ?>">
                        <?php echo e(cleanLang(__('lang.declined'))); ?>

                    </h1>
                </span>
                <!--revised-->
                <span class="js-estimate-statuses <?php echo e(runtimeEstimateStatus('revised', $bill->bill_status)); ?>"
                    id="estimate-status-revised">
                    <h1 class="text-uppercase <?php echo e(runtimeEstimateStatusColors('revised', 'text')); ?>">
                        <?php echo e(cleanLang(__('lang.revised'))); ?>

                    </h1>
                </span>
                <!--expired-->
                <span class="js-estimate-statuses <?php echo e(runtimeEstimateStatus('expired', $bill->bill_status)); ?>"
                    id="estimate-status-expired">
                    <h1 class="text-uppercase <?php echo e(runtimeEstimateStatusColors('expired', 'text')); ?>">
                        <?php echo e(cleanLang(__('lang.expired'))); ?>

                    </h1>
                </span>
            </span>
        </div>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/bill/components/elements/estimate/header-web.blade.php ENDPATH**/ ?>