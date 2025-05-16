<!--modal-->
<div class="row" id="js-trigger-clients-modal-add-edit" data-payload="<?php echo e($page['section'] ?? ''); ?>">
    <div class="col-lg-12">

        <?php if(isset($page['section']) && $page['section'] == 'edit' && $client->cust_type == 1): ?>
            <input type="hidden" name="cust_type" id="cust_type" value="<?php echo e($client->cust_type); ?>">
            
            <div class="form-group row ">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Customer Name*</label>
                <div class="col-sm-12 col-lg-9">
                    <input type="text" class="form-control form-control-sm" id="first_name" name="first_name"
                        placeholder="" value="<?php echo e($client->f_name ?? ' '); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label
                    class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.email_address'))); ?></label>
                <div class="col-sm-12 col-lg-9">
                    <input type="text" class="form-control form-control-sm" id="email" name="cu_email"
                        placeholder="" value="<?php echo e($client->u_email ?? ' '); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label ">Contact Number</label>
                <div class="col-sm-12 col-lg-9">
                    <input type="text" class="form-control form-control-sm" id="mobile_no" name="cu_mobile_no"
                        placeholder="" value="<?php echo e($client->client_phone ?? ' '); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Credit Limit</label>
                <div class="col-sm-12 col-lg-9">
                    <input class="form-control form-control-sm" id="credit_term" name="cu_credit_term" placeholder=""
                        value="<?php echo e($client->credit_term ?? ' '); ?>">
                </div>
            </div>
            <div class="form-group row ">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Currency</label>
                <div class="col-sm-12 col-lg-9">
                    <?php $currency = DB::table('xin_currencies')->get();
                    ?>
                    <select class="form-control" name="cu_currency">
                        <option>Select</option>
                        <?php foreach($currency as $cur){?>
                        <option value="<?php echo e($cur->currency_id); ?>"
                            <?php echo e(runtimePreselected($cur->currency_id, $client->currency ?? '')); ?>> <?php echo e($cur->name); ?>

                            (<?php echo e($cur->symbol); ?>) </option>
                        <?php }?>
                    </select>
                </div>
            </div>
        <?php endif; ?>

        <?php if(isset($page['section']) && $page['section'] == 'edit' && $client->cust_type == 0): ?>
            <input type="hidden" name="cust_type" id="cust_type" value="<?php echo e($client->cust_type); ?>">
            
            <div class="form-group row ">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Company Name*</label>
                <div class="col-sm-12 col-lg-9">
                    <input type="text" class="form-control form-control-sm" id="client_company_name"
                        name="client_company_name" placeholder="" value="<?php echo e($client->client_company_name ?? ' '); ?>">
                </div>
            </div>


            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Company UEN </label>
                <div class="col-sm-12 col-lg-9">
                    <input type="text" class="form-control form-control-sm" id="com_uen" name="com_uen"
                        placeholder="" value="<?php echo e($client->com_uen ?? ''); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label
                    class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.email_address'))); ?></label>
                <div class="col-sm-12 col-lg-9">
                    <input type="text" class="form-control form-control-sm" id="email" name="c_email"
                        placeholder="" value="<?php echo e($client->u_email ?? ' '); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label ">Contact Number</label>
                <div class="col-sm-12 col-lg-9">
                    <input type="text" class="form-control form-control-sm" id="c_mobile_no" name="mobile_no"
                        placeholder="" value="<?php echo e($client->client_phone ?? ' '); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Credit Limit</label>
                <div class="col-sm-12 col-lg-9">
                    <input class="form-control form-control-sm" id="credit_term" name="c_credit_term" placeholder=""
                        value="<?php echo e($client->credit_term ?? ' '); ?>">
                </div>
            </div>
            <div class="form-group row ">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Currency</label>
                <div class="col-sm-12 col-lg-9">
                    <?php $currency = DB::table('xin_currencies')->get();
                    ?>
                    <select class="form-control" name="c_currency">
                        <option value="">Select</option>
                        <?php foreach($currency as $cur){?>
                        <option value="<?php echo e($cur->currency_id); ?>"
                            <?php echo e(runtimePreselected($cur->currency_id, $client->currency ?? '')); ?>> <?php echo e($cur->name); ?>

                            (<?php echo e($cur->symbol); ?>) </option>
                        <?php }?>
                    </select>
                </div>
            </div>
        <?php endif; ?>

        <!--contact section-->
        <?php if(isset($page['section']) && $page['section'] == 'create'): ?>
            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Customer Type</label>
                <div class="col-sm-12 col-lg-9">
                    <select class="form-control form-control-sm" name="cust_type" id="customer">

                        <option value="0">Company</option>
                        <option value="1" selected>Individual</option>
                    </select>
                </div>
            </div>
            

            <div name="Company" id="Company" style="display:none">


                <div class="form-group row ">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Company
                        Name*</label>
                    <div class="col-sm-12 col-lg-9">
                        <input type="text" class="form-control form-control-sm" id="client_company_name"
                            name="client_company_name" placeholder="">
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Company UEN </label>
                    <div class="col-sm-12 col-lg-9">
                        <input type="text" class="form-control form-control-sm" id="com_uen" name="com_uen"
                            placeholder="" value="<?php echo e($client->com_uen ?? ''); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label
                        class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.email_address'))); ?>*</label>
                    <div class="col-sm-12 col-lg-9">
                        <input type="text" class="form-control form-control-sm" id="email" name="c_email"
                            placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label ">Contact Number</label>
                    <div class="col-sm-12 col-lg-9">
                        <input type="text" class="form-control form-control-sm" id="c_mobile_no" name="mobile_no"
                            placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Credit Limit</label>
                    <div class="col-sm-12 col-lg-9">
                        <input class="form-control form-control-sm" id="credit_term" name="c_credit_term"
                            placeholder="">
                    </div>
                </div>
                <div class="form-group row ">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Currency</label>
                    <div class="col-sm-12 col-lg-9">
                        <?php $currency = DB::table('xin_currencies')->get();
                        ?>
                        <select class="form-control" name="c_currency">
                            <option value="">Select</option>
                            <?php foreach($currency as $cur){?>
                            <option value="<?php echo e($cur->currency_id); ?>"> <?php echo e($cur->name); ?> (<?php echo e($cur->symbol); ?>)
                            </option>
                            <?php }?>
                        </select>
                    </div>
                </div>
            </div>
            <div name="Customer" id="Customer">

                <div class="form-group row ">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Customer
                        Name*</label>
                    <div class="col-sm-12 col-lg-9">
                        <input type="text" class="form-control form-control-sm" id="first_name" name="first_name"
                            placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label
                        class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.email_address'))); ?>*</label>
                    <div class="col-sm-12 col-lg-9">
                        <input type="text" class="form-control form-control-sm" id="email" name="cu_email"
                            placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label ">Contact Number</label>
                    <div class="col-sm-12 col-lg-9">
                        <input type="text" class="form-control form-control-sm" id="mobile_no"
                            name="cu_mobile_no" placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Credit Limit</label>
                    <div class="col-sm-12 col-lg-9">
                        <input class="form-control form-control-sm" id="credit_term" name="cu_credit_term"
                            placeholder="">
                    </div>
                </div>
                <div class="form-group row ">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Currency</label>
                    <div class="col-sm-12 col-lg-9">
                        <?php $currency = DB::table('xin_currencies')->get();
                        ?>
                        <select class="form-control" name="cu_currency">
                            <option value="">Select</option>
                            <?php foreach($currency as $cur){?>
                            <option value="<?php echo e($cur->currency_id); ?>"> <?php echo e($cur->name); ?> (<?php echo e($cur->symbol); ?>)
                            </option>
                            <?php }?>
                        </select>
                    </div>
                </div>
            </div>




            <div class="line"></div>
        <?php endif; ?>
        <!--contact section-->



        <!--notes-->
        <div class="row">
            <div class="col-12">
                <div><small><strong>* <?php echo e(cleanLang(__('lang.required'))); ?></strong></small></div>
            </div>
        </div>
    </div>
</div>


<script>
    $("#customer").on("change", function(e) {
        e.preventDefault();
        var a = $(this).val();
        if (a == 1) {
            $('#Company').css("display", "none")
            $('#Customer').css("display", "block")
            // $(".c_name").css("display","block")
        } else {
            $('#Company').css("display", "block")
            $('#Customer').css("display", "none")
            // $(".c_name").css("display","none")
        }
        // $("#" + $(this).val()).show();

    })
</script>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/clients/components/modals/add-edit-inc.blade.php ENDPATH**/ ?>