<div class="row">
    <div class="col-lg-12">

        <!--client-->
        <?php if(config('visibility.estimate_modal_client_fields')): ?>
            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label  required">Customer *</label>
                <div class="col-sm-12 col-lg-9">
                    <!--select2 basic search-->
                    <select name="bill_clientid" id="bill_clientid"
                        class="clients_and_projects_toggle form-control form-control-sm js-select2-basic-search-modal select2-hidden-accessible"
                        data-projects-dropdown="bill_projectid" data-feed-request-type="clients_projects"
                        data-ajax--url="<?php echo e(url('/')); ?>/feed/company_names">
                    </select>
                    <!--select2 basic search-->
                    </select>
                </div>
            </div>

            <!--projects-->
            <!-- <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.project'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="bill_projectid" name="bill_projectid"
                    disabled>
                </select>
            </div>
        </div> -->
        <?php endif; ?>
        <!--Quotation Subject Title-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label"> Quotation Subject Title</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" autocomplete="off" name="q_title"
                    value="<?php echo e($estimate->q_title ?? ''); ?>" autocomplete="off">
                <!-- <input class="mysql-date" type="hidden" id="bill_expiry_date_add_edit" name="bill_expiry_date" -->
                <!-- value="<?php echo e($estimate->bill_expiry_date ?? ''); ?>"> -->
            </div>
        </div>
        <!--estimate date-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Quotation Date*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control  form-control-sm pickadate" autocomplete="off"
                    name="bill_date_add_edit" value="<?php echo e(runtimeDatepickerDate($estimate->bill_date ?? '')); ?>"
                    autocomplete="off">
                <input class="mysql-date" type="hidden" name="bill_date" id="bill_date_add_edit"
                    value="<?php echo e($estimate->bill_date ?? ''); ?>">
            </div>
        </div>

        <!--expirey date-->
        <div class="form-group row">
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
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Project Site Address</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm " autocomplete="off" name="site_address"
                    value="<?php echo e($estimate->site_address ?? ''); ?>" autocomplete="off">
                <!-- <input class="mysql-date" type="hidden" id="bill_expiry_date_add_edit" name="bill_expiry_date" -->
                <!-- value="<?php echo e($estimate->bill_expiry_date ?? ''); ?>"> -->
            </div>
        </div>

        <!--PIC Name-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">PIC Name</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm " autocomplete="off" name="pic_name"
                    value="<?php echo e($estimate->pic_name ?? ''); ?>" autocomplete="off">
                <!-- <input class="mysql-date" type="hidden" id="bill_expiry_date_add_edit" name="bill_expiry_date" -->
                <!-- value="<?php echo e($estimate->bill_expiry_date ?? ''); ?>"> -->
            </div>
        </div>

        <!--PIC Contact-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">PIC Contact</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm " autocomplete="off" name="pic_contact"
                    value="<?php echo e($estimate->pic_contact ?? ''); ?>" autocomplete="off">
                <!-- <input class="mysql-date" type="hidden" id="bill_expiry_date_add_edit" name="bill_expiry_date" -->
                <!-- value="<?php echo e($estimate->bill_expiry_date ?? ''); ?>"> -->
            </div>
        </div>

        <!--PIC Email-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">PIC Email</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm " autocomplete="off" name="pic_email"
                    value="<?php echo e($estimate->pic_email ?? ''); ?>" autocomplete="off">
                <!-- <input class="mysql-date" type="hidden" id="bill_expiry_date_add_edit" name="bill_expiry_date" -->
                <!-- value="<?php echo e($estimate->bill_expiry_date ?? ''); ?>"> -->
            </div>
        </div>

        <!--estimate category-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Status</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="bill_categoryid"
                    name="bill_categoryid">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->category_id); ?>"
                            <?php echo e(runtimePreselected($estimate->bill_categoryid ?? '', $category->category_id)); ?>

                            data-cat="<?php echo e($category->category_name); ?>"><?php echo e(runtimeLang($category->category_name)); ?>

                        </option>
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
            <input type="hidden" id="cate_quo" name="cate_quo" value="Draft">
            <input type="hidden" id="" name="task_billable" value="on">
            <!--notes-->
            <div class="form-group row">
                <label class="col-12 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.notes'))); ?></label>
                <div class="col-12">
                    <textarea id="bill_notes" name="bill_notes" class="tinymce-textarea"><?php echo e($estimate->bill_notes ?? ''); ?></textarea>
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
<script>
    $("#bill_categoryid").on("change", function() {
        var selectedOption = $(this).find(':selected');
        var customData = selectedOption.data('cat');
        if (customData == "Approved from Management") {
            $("#cate_quo").val("Approval_from_Management");

        } else {
            $("#cate_quo").val(customData);

        }

    })
</script>
<?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/estimates/components/modals/add-edit-inc.blade.php ENDPATH**/ ?>