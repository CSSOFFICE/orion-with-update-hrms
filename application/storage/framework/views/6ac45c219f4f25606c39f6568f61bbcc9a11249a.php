<div class="row">
    <div class="col-lg-12">

        <?php if(isset($page['section']) && $page['section'] == 'create'): ?>

        <input type="hidden" name="clientid" id="clientid" value="<?php echo e($address->client_id ?? ''); ?>">


        

    <!--billing address section-->
    <div class="spacer row d-none">
        <div class="col-sm-12 col-lg-8">
            <span class="title"><?php echo e(cleanLang(__('lang.billing_address'))); ?></span class="title">
        </div>
        <div class="col-sm-12 col-lg-4">
            <div class="switch  text-right">
                <label class="required">
                    <input type="checkbox" name="add_client_option_bill_address"
                        id="add_client_option_bill_address" class="js-switch-toggle-hidden-content"
                        data-target="add_client_billing_address_section">
                    <span class="lever switch-col-light-blue"></span>
                </label>
            </div>
        </div>
    </div>
    <!--billing address section-->


    <!--billing address section-->
    <div id="add_client_billing_address_section" class="hiddenn">
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e("Person Incharge"); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="p_i"
                    name="p_i" value="<?php echo e($client->p_i ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row ">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e("Email"); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="p_email"
                    name="p_email" value="<?php echo e($client->p_email ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e("Contact number"); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="p_contact"
                    name="p_contact" value="<?php echo e($client->p_contact ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e("Postal Code"); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_billing_zip"
                    name="client_billing_zip" value="<?php echo e($client->client_billing_zip ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e("Unit Number"); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="p_contact"
                    name="p_unit" value="<?php echo e($client->p_unit ?? ''); ?>">
            </div>
        </div>

        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Address</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_billing_street"
                    name="client_billing_street" value="<?php echo e($client->client_billing_street ?? ''); ?>">
            </div>
        </div>


        <div class="form-group row hidden">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.city'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_billing_city"
                    name="client_billing_city" value="<?php echo e($client->client_billing_city ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row hidden">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.state'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_billing_state"
                    name="client_billing_state" value="<?php echo e($client->client_billing_state ?? ''); ?>">
            </div>
        </div>

        <div class="form-group row">
            <label for="example-month-input"
                class="col-sm-12 col-lg-3 col-form-label text-left"><?php echo e(cleanLang(__('lang.country'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <?php $selected_country = $client->client_billing_country ?? ''; ?>
                <select class="select2-basic select2-selection form-control form-control-sm" id="client_billing_country" data-plugin="select_hrm"
                    name="client_billing_country" data-placeholder="<?php echo e(cleanLang(__('lang.country'))); ?>" data-minimum-results-for-search="1">
                    <option></option>
                    <?php echo $__env->make('misc.country-list', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </select>
            </div>
        </div>


        <div class="line d-none"></div>
    </div>
    <!--billing address section-->

    

    <!--shipping address section-->
    <?php if(config('system.settings_clients_shipping_address') == 'enabled'): ?>
    <div class="spacer row d-none">
        <div class="col-sm-12 col-lg-8">
            <span class="title"><?php echo e(cleanLang(__('lang.shipping_address'))); ?></span class="title">
        </div>
        <div class="col-sm-12 col-lg-4">
            <div class="switch  text-right">
                <label>
                    <input type="checkbox" name="add_client_option_shipping_address"
                        id="add_client_option_shipping_address" class="js-switch-toggle-hidden-content"
                        data-target="add_client_shipping_address_section">
                    <span class="lever switch-col-light-blue"></span>
                </label>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!--shipping address section-->


    <!--shipping address section-->
    <?php if(config('system.settings_clients_shipping_address') == 'enabled'): ?>
    <div id="add_client_shipping_address_section" class="hidden">
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label">Address</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_shipping_street"
                    name="client_shipping_street" value="<?php echo e($client->client_shipping_street ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.city'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_shipping_city"
                    name="client_shipping_city" value="<?php echo e($client->client_shipping_city ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.state'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_shipping_state"
                    name="client_shipping_state" value="<?php echo e($client->client_shipping_state ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.zipcode'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_shipping_zip"
                    name="client_shipping_zip" value="<?php echo e($client->client_shipping_zip ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="example-month-input"
                class="col-sm-12 col-lg-3 col-form-label text-left"><?php echo e(cleanLang(__('lang.country'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <?php $selected_country = $client->client_shipping_country ?? ''; ?>
                <select class="select2-basic form-control form-control-sm" id="client_shipping_country"
                    name="client_shipping_country" data-minimum-results-for-search="1">
                    <option></option>
                    <?php echo $__env->make('misc.country-list', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </select>
            </div>
        </div>
        <div class="form-group form-group-checkbox row" id="expense_billable_option">
            <label
                class="col-sm-12 col-lg-3 col-form-label text-left"><?php echo e(cleanLang(__('lang.same_as_billing'))); ?></label>
            <div class="col-6 text-left p-t-5">
                <input type="checkbox" id="same_as_billing_address" name="same_as_billing_address"
                    class="filled-in chk-col-light-blue">
                <label for="same_as_billing_address"></label>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!--shipping address section-->


    <?php endif; ?>
    <?php if(isset($page['section']) && $page['section'] == 'edit'): ?>
    <!--social profile-->
    <div class="spacer row d-none">
        <div class="col-sm-12 col-lg-8">
            <span class="title"><?php echo e(cleanLang(__('lang.billing_address'))); ?></span class="title">
        </div>
        <div class="col-sm-12 col-lg-4">
            <div class="switch  text-right">
                <label class="required">
                    <input type="checkbox" name="add_client_option_bill_address"
                        id="add_client_option_bill_address" class="js-switch-toggle-hidden-content"
                        data-target="add_client_billing_address_section">
                    <span class="lever switch-col-light-blue"></span>
                </label>
            </div>
        </div>
    </div>
    <!--billing address section-->


    <!--billing address section-->
    <div id="add_client_billing_address_section" class="hiddenn">
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e("Person Incharge"); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="p_i"
                    name="p_i" value="<?php echo e($address->p_i ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e("Email"); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="p_email"
                    name="p_email" value="<?php echo e($address->p_email ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e("Contact number"); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="p_contact"
                    name="p_contact" value="<?php echo e($address->p_contact ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e("Postal Code"); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_billing_zip"
                    name="client_billing_zip" value="<?php echo e($address->zipcode ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e("Unit Number"); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="p_contact"
                    name="p_unit" value="<?php echo e($address->p_unit ?? ''); ?>">
            </div>
        </div>

        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Address</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_billing_street"
                    name="client_billing_street" value="<?php echo e($address->street ?? ''); ?>">
            </div>
        </div>

        <div class="form-group row hidden">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.city'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_billing_city"
                    name="client_billing_city" value="<?php echo e($address->city ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row hidden">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.state'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_billing_state"
                    name="client_billing_state" value="<?php echo e($address->state ?? ''); ?>">
            </div>
        </div>

        <div class="form-group row">
            <label for="example-month-input"
                class="col-sm-12 col-lg-3 col-form-label text-left"><?php echo e(cleanLang(__('lang.country'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <?php $selected_country = $address->country ?? ''; ?>
                <select class="select2-basic form-control form-control-sm" id="client_billing_country"
                    name="client_billing_country" data-minimum-results-for-search="1">
                    <option></option>
                    <?php echo $__env->make('misc.country-list', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </select>
            </div>
        </div>


        <div class="line d-none"></div>
    </div>
    <!--billing address section-->

    

    <!--shipping address section-->
    <?php if(config('system.settings_clients_shipping_address') == 'enabled'): ?>
    <div class="spacer row d-none">
        <div class="col-sm-12 col-lg-8">
            <span class="title"><?php echo e(cleanLang(__('lang.shipping_address'))); ?></span class="title">
        </div>
        <div class="col-sm-12 col-lg-4">
            <div class="switch  text-right">
                <label>
                    <input type="checkbox" name="add_client_option_shipping_address"
                        id="add_client_option_shipping_address" class="js-switch-toggle-hidden-content"
                        data-target="add_client_shipping_address_section">
                    <span class="lever switch-col-light-blue"></span>
                </label>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!--shipping address section-->


    <!--shipping address section-->
    <?php if(config('system.settings_clients_shipping_address') == 'enabled'): ?>
    <div id="add_client_shipping_address_section" class="hidden">
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label">Address</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_shipping_street"
                    name="client_shipping_street" value="<?php echo e($client->client_shipping_street ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row hidden">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.city'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_shipping_city"
                    name="client_shipping_city" value="<?php echo e($client->client_shipping_city ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row hidden">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.state'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_shipping_state"
                    name="client_shipping_state" value="<?php echo e($client->client_shipping_state ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.zipcode'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="client_shipping_zip"
                    name="client_shipping_zip" value="<?php echo e($client->client_shipping_zip ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="example-month-input"
                class="col-sm-12 col-lg-3 col-form-label text-left"><?php echo e(cleanLang(__('lang.country'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <?php $selected_country = $client->client_shipping_country ?? ''; ?>
                <select class="select2-basic form-control form-control-sm" id="client_shipping_country"
                    name="client_shipping_country" data-minimum-results-for-search="1">
                    <option></option>
                    <?php echo $__env->make('misc.country-list', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </select>
            </div>
        </div>
        <div class="form-group form-group-checkbox row" id="expense_billable_option">
            <label
                class="col-sm-12 col-lg-3 col-form-label text-left"><?php echo e(cleanLang(__('lang.same_as_billing'))); ?></label>
            <div class="col-6 text-left p-t-5">
                <input type="checkbox" id="same_as_billing_address" name="same_as_billing_address"
                    class="filled-in chk-col-light-blue">
                <label for="same_as_billing_address"></label>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!--shipping address section-->

    <!--social profile-->
    <?php endif; ?>

    <!--pass source-->
    <input type="hidden" name="source" value="<?php echo e(request('source')); ?>">

    <!--notes-->
    <div class="row">
        <div class="col-12">
            <div><small><strong>* <?php echo e(cleanLang(__('lang.required'))); ?></strong></small></div>
        </div>
    </div>
</div>
</div>
<script>
    $("#client_billing_zip").on("change", function(e) {
        e.preventDefault();
        let number = e.target.value;
        let data = async (key) => {
            let d = await fetch("https://www.onemap.gov.sg/api/common/elastic/search?searchVal=" + key + "&returnGeom=Y&getAddrDetails=Y&pageNum=1");
            let g = await d.json();
            let res = g.results[0].ADDRESS;
            if (res) {
                $("#client_billing_street").val(res);

            } else {

                $("#employee_address").val("Address Not Found");
            }


        }

        data(number);

    });
</script>
<?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/address/components/modals/add-edit-inc.blade.php ENDPATH**/ ?>