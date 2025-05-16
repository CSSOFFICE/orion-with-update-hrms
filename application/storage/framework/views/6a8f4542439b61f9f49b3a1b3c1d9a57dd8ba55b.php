<div class="row" id="js-projects-modal-add-edit" data-section="<?php echo e($page['section']); ?>">
    <div class="col-lg-12">
        <!--meta data - creatd by-->
        <?php if(isset($page['section']) && $page['section'] == 'edit'): ?>
            <div class="modal-meta-data">
                <small><strong><?php echo e(cleanLang(__('lang.created_by'))); ?>:</strong>
                    <?php echo e($project->f_name ?? runtimeUnkownUser()); ?> |
                    <?php echo e(runtimeDate($project->project_created)); ?></small>
            </div>
        <?php endif; ?>

        <!--category<>-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Project Category</label>
            <div class="col-sm-12 col-lg-9">
                <select class="form-control form-control-sm" id="project_cat" name="project_cat">
                    <option value="">Please Select</option>
                    <option value="<?php echo e($project->project_cat ?? 'Project'); ?>">Project</option>
                    <option value="<?php echo e($project->project_cat ?? 'Maintenance'); ?>">Maintenance</option>
                    <option value="<?php echo e($project->project_cat ?? 'Manpower supply'); ?>">Manpower supply</option>
                    <option value="<?php echo e($project->project_cat ?? 'Spare parts, Equipment'); ?>">Spare parts, Equipment
                    </option>
                    <option value="<?php echo e($project->project_cat ?? 'LSS (Life Safety System)'); ?>">LSS (Life Safety System)
                    </option>
                    <option value="<?php echo e($project->project_cat ?? 'TGCM'); ?>">TGCM</option>
                </select>
            </div>
        </div>
        <!--/#sn no-->

        <!--sn no<>-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Project Code*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="project_code" name="project_code"
                    placeholder="" value="<?php echo e($project->project_code ?? ''); ?>">
            </div>
        </div>
        <!--/#sn no-->

        <!--title<>-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.project_title'))); ?>*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="project_title" name="project_title"
                    placeholder="" value="<?php echo e($project->project_title ?? ''); ?>">
            </div>
        </div>
        <!--/#title-->

        <!--client<>-->
        <?php if(config('visibility.project_modal_client_fields')): ?>
            <div class="form-group row">
                <label
                    class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.client'))); ?>*</label>
                <div class="col-sm-12 col-lg-9">
                    <!--select2 basic search-->
                    <select name="project_clientid" id="project_clientid"
                        class="form-control form-control-sm js-select2-basic-search-modal select2-hidden-accessible"
                        data-ajax--url="<?php echo e(url('/')); ?>/feed/company_names"></select>
                    <!--select2 basic search-->
                    </select>
                </div>
            </div>
        <?php endif; ?>
        <!--/#client-->


        <!--dates<>-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.start_date'))); ?>*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm pickadate" name="project_date_start"
                    autocomplete="off" value="<?php echo e(runtimeDatepickerDate($project->project_date_start ?? '')); ?>">
                <input class="mysql-date" type="hidden" name="project_date_start" id="project_date_start"
                    value="<?php echo e($project->project_date_start ?? ''); ?>">
            </div>
        </div>


        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.extension_of_time_period'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="number" class="form-control form-control-sm" name="extension_of_time_period"
                    autocomplete="off" value="<?php echo e($project->extension_of_time_period ?? ''); ?>">
            </div>
        </div>

        <!--dates<>-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.deadline'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm pickadate project_date_due" name="project_date_due"
                    autocomplete="off" value="<?php echo e(runtimeDatepickerDate($project->project_date_due ?? '')); ?>">
                <input class="mysql-date" type="hidden" name="project_date_due" id="project_date_due"
                    value="<?php echo e($project->project_date_due ?? ''); ?>">
            </div>
        </div>

        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.defects_liability_period'))); ?></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm pickadate defects_liability_period" name="defects_liability_period"
                    autocomplete="off" value="<?php echo e($project->defects_liability_period ?? ''); ?>">
            </div>
        </div>

        <!--assigned team members<>-->
        <?php if(config('visibility.project_modal_assign_fields')): ?>
            <div class="form-group row">
                <label
                    class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.assigned'))); ?></label>
                <div class="col-sm-12 col-lg-9">
                    <select name="assigned" id="assigned"
                        class="form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
                        multiple="multiple" tabindex="-1" aria-hidden="true">
                        <!--array of assigned-->
                        <?php if(isset($page['section']) && $page['section'] == 'edit' && isset($project->assigned)): ?>
                            <?php $__currentLoopData = $project->assigned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $assigned[] = $user->id; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        <!--/#array of assigned-->
                        <!--users list-->
                        <?php $__currentLoopData = config('system.team_members'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>"
                                <?php echo e(runtimePreselectedInArray($user->id ?? '', $assigned ?? [])); ?>>
                                <?php echo e($user->full_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <!--/#users list-->
                    </select>
                </div>
            </div>
        <?php endif; ?>
        <!--/#assigned team members-->

        <!--project manager<>-->
        <?php if(config('visibility.project_modal_assign_fields')): ?>
            <div class="form-group row">
                <label
                    class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.manager'))); ?>

                    <a class="align-middle font-16 toggle-collapse" href="#project_manager_info" role="button"><i
                            class="ti-info-alt text-themecontrast"></i></a></label>
                <div class="col-sm-12 col-lg-9">
                    <select name="manager" id="manager" class="select2-basic form-control form-control-sm">
                        <!--array of assigned-->
                        <?php if(isset($page['section']) && $page['section'] == 'edit' && isset($project->managers)): ?>
                            <?php $__currentLoopData = $project->managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $manager[] = $user->id; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        <!--/#array of assigned-->
                        <!--users list-->
                        <?php $__currentLoopData = config('system.team_members'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option></option>
                            <option value="<?php echo e($user->id); ?>"
                                <?php echo e(runtimePreselectedInArray($user->id ?? '', $manager ?? [])); ?>>
                                <?php echo e($user->full_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <!--/#users list-->
                    </select>
                </div>
            </div>
        <?php endif; ?>
        <div class="form-group row ">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Contact Sum</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="contact_sub" name="project_sn"
                    placeholder="" value="<?php echo e($project->project_sn ?? ''); ?>">
            </div>
        </div>

        <!--/#project manager-->
        <div class="collapse" id="project_manager_info">
            <div class="alert alert-info"><?php echo e(cleanLang(__('lang.project_manager_info'))); ?></div>
        </div>

        <!--[billing details]-->
        <!-- <?php if(auth()->user()->role->role_projects_billing == 2): ?>
-->
        <!-- <div class="highlighted-panel"> -->
        <!--billing type-->
        <!-- <div class="form-group row">
                <label for="example-month-input"
                    class="col-sm-12 col-lg-4 col-form-label text-left"><?php echo e(cleanLang(__('lang.billing'))); ?></label>
                <div class="col-sm-12 col-lg-4">
                    <input type="number" class="form-control form-control-sm" id="project_billing_rate"
                        name="project_billing_rate" placeholder="" value="<?php echo e($project['project_billing_rate'] ?? ''); ?>">

                </div>
                <div class="col-sm-12 col-lg-4">
                    <select class="select2-basic form-control form-control-sm" id="project_billing_type"
                        data-allow-clear="false" name="project_billing_type">
                        <option value="hourly" <?php echo e(runtimePreselected('hourly', $project['project_billing_type'] ?? '')); ?>>
                            <?php echo e(cleanLang(__('lang.hourly'))); ?></option>
                        <option value="fixed" <?php echo e(runtimePreselected('fixed', $project['project_billing_type'] ?? '')); ?>>
                            <?php echo e(cleanLang(__('lang.fixed_fee'))); ?></option>
                    </select>
                </div>
            </div> -->
        <!--estimated hours-->
        <!-- <div class="form-group row">
                <label class="col-sm-12 col-lg-4 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.estimated_hours'))); ?>

                    <a class="align-middle font-16 toggle-collapse" href="#project_estimated_hours_info" role="button"
                        ><i class="ti-info-alt text-themecontrast"></i></a></label>
                <div class="col-sm-12 col-lg-4">
                    <input type="number" class="form-control form-control-sm" id="project_billing_estimated_hours"
                        name="project_billing_estimated_hours" placeholder=""
                        value="<?php echo e($project['project_billing_estimated_hours'] ?? ''); ?>">
                </div>
                <div class="collapse col-sm-12 m-t-15" id="project_estimated_hours_info">
                    <div class="alert alert-info"><?php echo e(cleanLang(__('lang.project_estimated_hours_info'))); ?></div>
                </div>
            </div> -->
        <!--cost estimate-->
        <!-- <div class="form-group row m-b-0">
                <label class="col-sm-12 col-lg-4 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.costs_estimate'))); ?> <a
                        class="align-middle font-16 toggle-collapse" href="#project_cost_estimate_info" role="button"
                        ><i class="ti-info-alt text-themecontrast"></i></a></label>
                <div class="col-sm-12 col-lg-4">
                    <input type="number" class="form-control form-control-sm" id="project_billing_costs_estimate"
                        name="project_billing_costs_estimate" placeholder=""
                        value="<?php echo e($project['project_billing_costs_estimate'] ?? ''); ?>">
                </div>
                <div class="collapse col-sm-12 m-t-15" id="project_cost_estimate_info">
                    <div class="alert alert-info"><?php echo e(cleanLang(__('lang.project_cost_estimate_info'))); ?></div>
                </div>
            </div>
        </div> -->
        <!--
<?php endif; ?> -->
        <!--/#[billing details]-->


        <!--edit description- toggle<>-->
        <div class="spacer row">
            <div class="col-sm-8">
                <span class="title">Project <?php echo e(cleanLang(__('lang.description'))); ?></span class="title">
            </div>
            <div class="col-sm-12 col-lg-4">
                <div class="switch  text-right">
                    <label>
                        <input type="checkbox" class="js-switch-toggle-hidden-content"
                            data-target="edit_project_description_toggle">
                        <span class="lever switch-col-light-blue"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="hidden" id="edit_project_description_toggle">
            <textarea id="project_description" name="project_description" class="tinymce-textarea"><?php echo e($project->project_description ?? ''); ?></textarea>
            <div class="line m-t-30"></div>
        </div>
        <!--edit description- toggle-->


        <!--project options-->
        <div class="spacer row">
            <div class="col-sm-8">
                <span class="title"><?php echo e(cleanLang(__('lang.additional_settings'))); ?></span class="title">
            </div>
            <div class="col-sm-4 text-right">
                <div class="switch">
                    <label>
                        <input type="checkbox" class="js-switch-toggle-hidden-content"
                            data-target="edit_project_setings">
                        <span class="lever switch-col-light-blue"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="hidden" id="edit_project_setings">



            <!--spacer-->
            <?php if(config('visibility.project_modal_project_permissions')): ?>
                <div class="spacer row">
                    <div class="col-sm-8">
                        <span class="title"><?php echo e(cleanLang(__('lang.assigned_user_permissions'))); ?></span>
                    </div>
                    <div class="col-sm-4">
                        <div class="switch  text-right">
                            <label>
                                <input type="checkbox" name="show_more_settings_projects"
                                    id="show_more_settings_projects" class="js-switch-toggle-hidden-content"
                                    data-target="edit_project_assigned_permissions">
                                <span class="lever switch-col-light-blue"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <!--spacer-->
                <!--option toggle-->
                <div class="hidden highlighted-panel" id="edit_project_assigned_permissions">
                    <div class="form-group form-group-checkbox row m-b-0">
                        <label class="col-5 col-form-label text-left"><?php echo e(cleanLang(__('lang.task_collaboration'))); ?>

                            <a class="align-middle font-16 toggle-collapse" href="#info_task_collaboration"
                                role="button"><i class="ti-info-alt text-themecontrast"></i></a> </label>
                        <div class="col-7 text-left p-t-5">
                            <input type="checkbox" id="assignedperm_tasks_collaborate"
                                name="assignedperm_tasks_collaborate" class="filled-in chk-col-light-blue"
                                <?php echo e(runtimePrechecked($project['assignedperm_tasks_collaborate'] ?? '')); ?>>
                            <label for="assignedperm_tasks_collaborate"></label>
                        </div>
                    </div>
                    <!--info: taskcollaborations-->
                    <div class="collapse" id="info_task_collaboration">
                        <div class="alert alert-info"><?php echo e(cleanLang(__('lang.task_collaboration_info'))); ?></div>
                    </div>
                </div>
                <!--option toggle-->


                <!--spacer-->
                <div class="spacer row">
                    <div class="col-sm-8">
                        <span class="title"><?php echo e(cleanLang(__('lang.client_project_permissions'))); ?></span
                            class="title">
                    </div>
                    <div class="col-sm-4">
                        <div class="switch text-right">
                            <label>
                                <input type="checkbox" name="show_more_settings_projects2"
                                    id="show_more_settings_projects2" class="js-switch-toggle-hidden-content"
                                    data-target="edit_project_permissions_tasks">
                                <span class="lever switch-col-light-blue"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <!--spacer-->
                <!--permissions-->
                <div id="edit_project_permissions_tasks" class="hidden highlighted-panel">
                    <!--permission - view tasks-->
                    <div class="form-group form-group-checkbox row">
                        <label class="col-5 col-form-label text-left"><?php echo e(cleanLang(__('lang.view_tasks'))); ?></label>
                        <div class="col-7 text-left p-t-5">
                            <input type="checkbox" id="clientperm_tasks_view" name="clientperm_tasks_view"
                                class="filled-in chk-col-light-blue"
                                <?php echo e(runtimePrechecked($project['clientperm_tasks_view'] ?? '')); ?>>
                            <label for="clientperm_tasks_view"></label>
                        </div>
                    </div>
                    <!--permission - task participation-->
                    <div class="form-group form-group-checkbox row">
                        <label
                            class="col-5 col-form-label text-left required"><?php echo e(cleanLang(__('lang.task_participation'))); ?>**</label>
                        <div class="col-7 text-left p-t-5">
                            <input type="checkbox" id="clientperm_tasks_collaborate"
                                name="clientperm_tasks_collaborate" class="filled-in chk-col-light-blue"
                                <?php echo e(runtimePrechecked($project['clientperm_tasks_collaborate'] ?? '')); ?>>
                            <label for="clientperm_tasks_collaborate"></label>
                        </div>
                    </div>
                    <!--permission - create tasks-->
                    <div class="form-group form-group-checkbox row">
                        <label
                            class="col-5 col-form-label text-left required"><?php echo e(cleanLang(__('lang.create_tasks'))); ?>**</label>
                        <div class="col-7 text-left p-t-5">
                            <input type="checkbox" id="clientperm_tasks_create" name="clientperm_tasks_create"
                                class="filled-in chk-col-light-blue"
                                <?php echo e(runtimePrechecked($project['clientperm_tasks_create'] ?? '')); ?>>
                            <label for="clientperm_tasks_create"></label>
                        </div>
                    </div>
                    <div class="line"></div>
                    <!--permission - view timesheets-->
                    <div class="form-group form-group-checkbox row">
                        <label
                            class="col-5 col-form-label text-left"><?php echo e(cleanLang(__('lang.view_time_sheets'))); ?></label>
                        <div class="col-7 text-left p-t-5">
                            <input type="checkbox" id="clientperm_timesheets_view" name="clientperm_timesheets_view"
                                class="filled-in chk-col-light-blue"
                                <?php echo e(runtimePrechecked($project['clientperm_timesheets_view'] ?? '')); ?>>
                            <label for="clientperm_timesheets_view"></label>
                        </div>
                    </div>
                    <!--permission - view expenses-->
                    <div class="form-group form-group-checkbox row">
                        <label
                            class="col-5 col-form-label text-left"><?php echo e(cleanLang(__('lang.view_expenses'))); ?></label>
                        <div class="col-7 text-left p-t-5">
                            <input type="checkbox" id="clientperm_expenses_view" name="clientperm_expenses_view"
                                class="filled-in chk-col-light-blue"
                                <?php echo e(runtimePrechecked($project['clientperm_expenses_view'] ?? '')); ?>>
                            <label for="clientperm_expenses_view"></label>
                        </div>
                    </div>

                    <div><small class="text-bold">**
                            <?php echo e(cleanLang(__('lang.if_items_selected_then_viewing_perm'))); ?></small></div>
                </div>
                <!--permissions-->
            <?php endif; ?>



            <!--project progress-->
            <div class="spacer row">
                <div class="col-sm-8">
                    <span class="title"><?php echo e(cleanLang(__('lang.progress'))); ?></span class="title">
                </div>
                <div class="col-sm-4 text-right">
                    <div class="switch">
                        <label>
                            <input type="checkbox" class="js-switch-toggle-hidden-content"
                                data-target="edit_project_progress">
                            <span class="lever switch-col-light-blue"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!--project progress-->
            <div class="hidden" id="edit_project_progress">

                <div class="form-group form-group-checkbox row">
                    <label
                        class="col-4 col-form-label text-left"><?php echo e(cleanLang(__('lang.set_progress_manually'))); ?>?</label>
                    <div class="col-7 text-left p-t-5">
                        <input type="checkbox" id="project_progress_manually" name="project_progress_manually"
                            class="filled-in chk-col-light-blue"
                            <?php echo e(runtimePrechecked($project['project_progress_manually'] ?? '')); ?>>
                        <label for="project_progress_manually"></label>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-10 p-l-30">
                        <div id="edit_project_progress_bar"></div>
                    </div>
                    <div class="col-sm-2 text-right">
                        <strong>
                            <span id="edit_project_progress_display">20</span>%</strong>
                    </div>
                </div>
                <input type="hidden" name="project_progress" value="<?php echo e($project->project_progress ?? ''); ?>"
                    id="project_progress" />
            </div>


            <!--project options-->
            <div class="spacer row">
                <div class="col-sm-8">
                    <span class="title"><?php echo e(cleanLang(__('lang.options'))); ?></span class="title">
                </div>
                <div class="col-sm-4 text-right">
                    <div class="switch">
                        <label>
                            <input type="checkbox" class="js-switch-toggle-hidden-content"
                                data-target="edit_project_options">
                            <span class="lever switch-col-light-blue"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!--project options-->
            <div class="hidden" id="edit_project_options">
                <div class="form-group row">
                    <label for="example-month-input"
                        class="col-sm-12 col-lg-3 col-form-label text-left required"><?php echo e(cleanLang(__('lang.category'))); ?></label>
                    <div class="col-sm-12 col-lg-9">
                        <select class="select2-basic form-control form-control-sm" id="project_categoryid"
                            name="project_categoryid">
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->category_id); ?>"
                                    <?php echo e(runtimePreselected($project->project_categoryid ?? '', $category->category_id)); ?>>
                                    <?php echo e(runtimeLang($category->category_name)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>


                <!--tags-->
                <div class="form-group row">
                    <label
                        class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.tags'))); ?></label>
                    <div class="col-sm-12 col-lg-9">
                        <select name="tags" id="tags"
                            class="form-control form-control-sm select2-multiple <?php echo e(runtimeAllowUserTags()); ?> select2-hidden-accessible"
                            multiple="multiple" tabindex="-1" aria-hidden="true">
                            <!--array of selected tags-->
                            <?php if(isset($page['section']) && $page['section'] == 'edit'): ?>
                                <?php $__currentLoopData = $project->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                <!--/#tags-->
            </div>





            <!--pass source-->
            <input type="hidden" name="source" value="<?php echo e(request('source')); ?>">

        </div>


        <!--redirect to project-->
        <?php if(config('visibility.project_show_project_option')): ?>
            <div class="form-group form-group-checkbox row">
                <div class="col-12 text-left p-t-5">
                    <input type="checkbox" id="show_after_adding" name="show_after_adding"
                        class="filled-in chk-col-light-blue" checked="checked">
                    <label for="show_after_adding"><?php echo e(cleanLang(__('lang.show_project_after_its_created'))); ?></label>
                </div>
            </div>
        <?php endif; ?>
        <!--notes-->
        <div class="row">
            <div class="col-12">
                <div><small><strong>* <?php echo e(cleanLang(__('lang.required'))); ?></strong></small></div>
            </div>
        </div>
    </div>
</div>


<?php if(isset($page['section']) && $page['section'] == 'edit'): ?>
    <!--dynamic inline - set dynamic project progress-->
    <script>
        NX.varInitialProjectProgress = "<?php echo e($project['project_progress']); ?>";
    </script>
<?php endif; ?>


<script>
    $(document).ready(function() {

        $(document).on('change', '.project_date_due', function() {

            var deadline = $(this).val();
            if (deadline !== '') {

                var deadlineParts = deadline.split('-');
                var day = parseInt(deadlineParts[0], 10);
                var month = parseInt(deadlineParts[1], 10);
                var year = parseInt(deadlineParts[2], 10);

                var deadlineDate = new Date(year, month - 1, day);

                deadlineDate.setMonth(deadlineDate.getMonth() + 12);

                var newDay = deadlineDate.getDate();
                var newMonth = deadlineDate.getMonth() + 1;
                var newYear = deadlineDate.getFullYear();
                var newDate = newDay + '-' + newMonth + '-' + newYear;
               // console.log(newDate);

                $('.defects_liability_period').val(newDate);
            }
        });
    });
</script>


<?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/projects/components/modals/add-edit-inc.blade.php ENDPATH**/ ?>