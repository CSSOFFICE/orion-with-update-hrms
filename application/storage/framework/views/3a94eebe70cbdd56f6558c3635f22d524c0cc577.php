<?php

$clients=DB::table('clients')->get();

$savedOptions =explode(',',$estimate->quotation_options);
$originalDate = date('d-m-Y');

// Convert to DateTime object
$date = DateTime::createFromFormat('d-m-Y', $originalDate);

// Subtract one day
$date->modify('+1 month');

// Return updated date
$ddd=$date->format('d-m-Y');

?>
<div class="row">
    <div class="col-lg-12">

        <!--client-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Select Quotation Type</label>
            <div class="col-sm-12 col-lg-9">
                <div class="form-check form-check-inline">
                    <input class="form-check-input quotation_type_b" type="radio" name="quotation_type" id="contract_document" value="<?php echo e($estimate->quotation_type); ?>" <?php echo e($estimate->quotation_type==1?'checked':''); ?> onclick="setQuotationType(1)">
                    <label class="form-check-label" for="contract_document">Contract Document</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input quotation_type_a" type="radio" name="quotation_type" id="simple_quotation" value="<?php echo e($estimate->quotation_type); ?>" <?php echo e($estimate->quotation_type==2?'checked':''); ?> onclick="setQuotationType(2)">
                    <label class="form-check-label" for="simple_quotation">Simple Quotation</label>
                </div>
            </div>
            <input type="hidden" name="quotation_type_hidden" id="quotation_type_hidden">
        </div>

        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Select Quotation Format</label>
            <div class="col-sm-12 col-lg-9">
                <select class="form-control form-control-sm" id="quotation_options" name="quotation_options" <?php echo e($estimate->quotation_type==1?'multiple':''); ?>>
                    <option value="summary" <?php echo e(in_array('summary', $savedOptions ?? []) ? 'selected' : ''); ?>>
                        Summary
                    </option>
                    <option value="preliminaries" <?php echo e(in_array('preliminaries', $savedOptions ?? []) ? 'selected' : ''); ?>>
                        Preliminaries
                    </option>
                    <option value="insurance" <?php echo e(in_array('insurance', $savedOptions ?? []) ? 'selected' : ''); ?>>
                        Insurance
                    </option>
                    <option value="schedule_of_works" <?php echo e(in_array('schedule_of_works', $savedOptions ?? []) ? 'selected' : ''); ?>>
                        Schedule of Works
                    </option>
                    <option value="plumbing_sanity" <?php echo e(in_array('plumbing_sanity', $savedOptions ?? []) ? 'selected' : ''); ?>>
                        Plumbing & Sanity
                    </option>
                    <option value="elec_acme" <?php echo e(in_array('elec_acme', $savedOptions ?? []) ? 'selected' : ''); ?>>
                        ELEC & ACME
                    </option>
                    <option value="external_works" <?php echo e(in_array('external_works', $savedOptions ?? []) ? 'selected' : ''); ?>>
                        External Works
                    </option>
                    <option value="pc_ps_sums" <?php echo e(in_array('pc_ps_sums', $savedOptions ?? []) ? 'selected' : ''); ?>>
                        PC & PS Sums
                    </option>
                </select>
            </div>
        </div>


        <input type="hidden" value="<?php echo e($estimate->bill_clientid); ?>" name="bill_clientid">
        <input type="hidden" value="<?php echo e($estimate->bill_clientid); ?>" name="bill_categoryid">
        <input type="hidden" value="<?php echo e($estimate->bill_projectid); ?>" name="bill_projectid">






        <!--Quotation Subject Title-->
        <div class="form-group row hidden">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label"> Quotation Subject Title</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" autocomplete="off" name="q_title"
                    value="<?php echo e($estimate->q_title ?? ''); ?>" autocomplete="off">
                <!-- <input class="mysql-date" type="hidden" id="bill_expiry_date_add_edit" name="bill_expiry_date" -->
                <!-- value="<?php echo e($estimate->bill_expiry_date ?? ''); ?>"> -->
            </div>
        </div>
        <!--estimate date-->
        <div class="form-group row hidden">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Quotation Date*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm pickadate" autocomplete="off"
                    name="bill_date_add_edit" value="<?php echo e(date('Y-m-d')); ?>"
                    autocomplete="off">
                <input class="mysql-date" type="hidden" name="bill_date" id="bill_date_add_edit"
                    value="<?php echo e(date('Y-m-d')); ?>">
            </div>
        </div>


        <!--expirey date-->
        <div class="form-group row hidden">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.expiry_date'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm pickadate" autocomplete="off"
                    name="bill_expiry_date_add_edit"
                    value="<?php echo e(runtimeDatepickerDate($estimate->bill_expiry_date ?? '')); ?>" autocomplete="off">
                <input class="mysql-date" type="hidden" id="bill_expiry_date_add_edit" name="bill_expiry_date"
                    value="<?php echo e($estimate->bill_expiry_date ?? ''); ?>">
            </div>
        </div>

        <!--Project Site Address-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">PIC Address</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm " autocomplete="off" id="p_id_add"
                    name="site_address" value="<?php echo e($estimate->site_address ?? ''); ?>" autocomplete="off">
                <!-- <input class="mysql-date" type="hidden" id="bill_expiry_date_add_edit" name="bill_expiry_date" -->
                <!-- value="<?php echo e($estimate->bill_expiry_date ?? ''); ?>"> -->
            </div>
        </div>

        <!--PIC Name-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">PIC Name</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm " autocomplete="off" id="p_id_name"
                    name="pic_name" value="<?php echo e($estimate->pic_name ?? ''); ?>" autocomplete="off">
                <!-- <input class="mysql-date" type="hidden" id="bill_expiry_date_add_edit" name="bill_expiry_date" -->
                <!-- value="<?php echo e($estimate->bill_expiry_date ?? ''); ?>"> -->
            </div>
        </div>

        <!--PIC Contact-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">PIC Contact</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm " autocomplete="off" id="p_id_contact"
                    name="pic_contact" value="<?php echo e($estimate->pic_contact ?? ''); ?>" autocomplete="off">
                <!-- <input class="mysql-date" type="hidden" id="bill_expiry_date_add_edit" name="bill_expiry_date" -->
                <!-- value="<?php echo e($estimate->bill_expiry_date ?? ''); ?>"> -->
            </div>
        </div>

        <!--PIC Email-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">PIC Email</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm " autocomplete="off" id="p_id_email"
                    name="pic_email" value="<?php echo e($estimate->pic_email ?? ''); ?>" autocomplete="off">
                <!-- <input class="mysql-date" type="hidden" id="bill_expiry_date_add_edit" name="bill_expiry_date" -->
                <!-- value="<?php echo e($estimate->bill_expiry_date ?? ''); ?>"> -->
            </div>
        </div>
        <input type="hidden" id="task_client_visibility" name="task_client_visibility" value="on" />
        <!--estimate category-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Status</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="bill_categoryid"
                    name="bill_categoryid">
                    <?php $__currentLoopData = config('settings.quo_statuses'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>"><?php echo e(runtimeLang($key)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="line"></div>

        <!--other details-->
        <div class="spacer row">
            <div class="col-sm-12 col-lg-8">
                <span class="title"><?php echo e(cleanLang(__('lang.additional_information'))); ?></span class="title">
            </div>
            <div class="col-sm-12 col-lg-4">
                <div class="switch  text-right">
                    <label>
                        <input type="checkbox" class="js-switch-toggle-hidden-content"
                            data-target="edit_bill_options_toggle">
                        <span class="lever switch-col-light-blue"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="hidden" id="edit_bill_options_toggle">

            <!--tags-->
            <div class="form-group row">
                <label class="col-12 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.tags'))); ?></label>
                <div class="col-12">
                    <select name="tags" id="tags"
                        class="form-control form-control-sm select2-multiple <?php echo e(runtimeAllowUserTags()); ?> select2-hidden-accessible"
                        multiple="multiple" tabindex="-1" aria-hidden="true">
                        <!--array of selected tags-->
                        <?php if(isset($page['section']) && $page['section'] == 'edit'): ?>
                        <?php $__currentLoopData = $estimate->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $selected_tags[] = $tag->tag_title ; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        <!--/#array of selected tags-->
                        <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tag->tag_title); ?>"
                            <?php echo e(runtimePreselectedInArray($tag->tag_title ?? '', $selected_tags ?? [])); ?>>
                            <?php echo e($tag->tag_title); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <!--notes-->
            <div class="form-group row">
                <label class="col-12 text-left control-label col-form-label">Notes</label>
                <div class="col-12">
                    <textarea id="bill_notes" name="bill_notes" class="tinymce-textarea">
                      </textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 text-left control-label col-form-label">Exclution</label>
                <div class="col-12">
                    <textarea id="bill_notes" name="bill_exclution" class="tinymce-textarea">
                        1) site storage and office are<br>
<br>
                        2) PE and Authority submissions<br><br>

                        3) BMS, card acess,LSS and smoke detector by client<br><br>

                        4) Any modification and additional works not mentioned in this office</textarea>
                </div>
            </div>

            <!--terms-->
            <div class="form-group row">
                <label class="col-12 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.terms'))); ?></label>
                <div class="col-12">
                    <textarea id="bill_terms" name="bill_terms" class="tinymce-textarea">
                        <?php if(isset($page['section']) && $page['section'] == 'create'): ?>
                        <?php echo e(config('system.settings_estimates_default_terms_conditions')); ?>

                        <?php else: ?>
                        <?php echo e($estimate->bill_terms ?? ''); ?>

                        <?php endif; ?>
                </textarea>
                </div>
            </div>
        </div>

        <!--source-->
        <input type="hidden" name="source" value="<?php echo e(request('source')); ?>">

        <!--notes-->
        <div class="row">
            <div class="col-12">
                <div><small><strong>* <?php echo e(cleanLang(__('lang.required'))); ?></strong></small></div>
            </div>
        </div>
    </div>
</div>


|<script>
    $(document).ready(function() {
        let value = 0;
        if ($('.quotation_type_a').val()) {
            value = $('.quotation_type_a').val();

        } else {
            value = $('.quotation_type_b').val();

        }


        const $selectBoxContainer = $('#quotation_options');
        if (value == 1) {
            $selectBoxContainer.prop('multiple', true);

            $selectBoxContainer.find('option').on('mousedown', function(e) {
                e.preventDefault();
                const $this = $(this);
                $this.prop('selected', !$this.prop('selected'));
                return false;
            });
        } else {
            $selectBoxContainer.prop('multiple', false);
            $selectBoxContainer.find('option').off('mousedown');
        }

        const $selectBoxDisplayContainer = $('#select-box-container');
        if ($selectBoxDisplayContainer.length) {
            if (value == 1 || value == 2) {
                $selectBoxDisplayContainer.show();
            } else {
                $selectBoxDisplayContainer.hide();
            }
        }
    })

    function setQuotationType(value) {
        $('#quotation_type_hidden').val(value);

        const $selectBoxContainer = $('#quotation_options');
        if (value == 1) {
            $selectBoxContainer.prop('multiple', true);

            $selectBoxContainer.find('option').on('mousedown', function(e) {
                e.preventDefault();
                const $this = $(this);
                $this.prop('selected', !$this.prop('selected'));
                return false;
            });
        } else {
            $selectBoxContainer.prop('multiple', false);
            $selectBoxContainer.find('option').off('mousedown');
        }

        const $selectBoxDisplayContainer = $('#select-box-container');
        if ($selectBoxDisplayContainer.length) {
            if (value == 1 || value == 2) {
                $selectBoxDisplayContainer.show();
            } else {
                $selectBoxDisplayContainer.hide();
            }
        }
    }
</script>


<script>
    $("#bill_clientid").on("change", function(e) {
        let id = $(this).val();

        $.ajax({
            url: "<?php echo e(route('getDefaultAddress')); ?>",
            type: "get",
            dataType: "json",
            data: {
                id: id,
                add: 1,
            },
            success: function(res) {
                let op = `<option value=""  selected>Select Address</option>`;
                op += res.map(re => `<option data-p_i="${re.p_i}" data-street="${re.street}" data-p_contact="${re.p_contact}" data-p_email="${re.p_email}">${re.street} </option>`).join('');

                $("#billing_id").html(op);



            }

        })



    })
    $("#billing_id").on("change", function(e) {
        let selectedOption = $(this).find('option:selected');


        $("#p_id_name").val(selectedOption.data('p_i'));
        $("#p_id_add").val(selectedOption.data('street'));

        $("#p_id_contact").val(selectedOption.data('p_contact'));
        $("#p_id_email").val(selectedOption.data('p_email'));



    })
</script>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/variation/components/modals/add-edit-inc.blade.php ENDPATH**/ ?>