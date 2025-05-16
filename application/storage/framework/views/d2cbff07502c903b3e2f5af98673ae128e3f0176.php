<!-- Column -->
<div class="card" id="project_details" data-progress="<?php echo e($project->project_progress); ?>">
    <div class="card-body p-t-10 p-b-10" id="project_progress_container">
        <!--project progress-->
        <div class="d-flex no-block">
            <div class="align-self-end no-shrink">
                <h5 class="m-b-0"><?php echo e(cleanLang(__('lang.progress'))); ?></h5>
                <!--[team]-->
                <?php if(auth()->user()->is_team): ?>
                <?php if($project->project_progress_manually == 'yes'): ?>
                <h6 class="text-muted"><?php echo e(cleanLang(__('lang.manually_set_progress'))); ?></h6>
                <?php else: ?>
                <h6 class="text-muted"><?php echo e(cleanLang(__('lang.task_based_progress'))); ?></h6>
                <?php endif; ?>
                <?php else: ?>
                <!--[client]-->
                <h6 class="text-muted"><?php echo e(cleanLang(__('lang.project_progress'))); ?></h6>
                <?php endif; ?>
            </div>
            <div class="ml-auto">
                <div id="project_progress_chart"></div>
            </div>
        </div>
        <!--project progress-->
    </div>
    <!--hidden-->
    <div class="card-body p-t-0 p-b-0 d-none" id="project_progress_hidden">
        <div>
            <table class="table no-border m-b-0">
                <tbody>
                    <tr>
                        <td class="p-l-0 p-t-5">
                            <h5 class="m-b-0"><?php echo e(cleanLang(__('lang.progress'))); ?></h5>
                            <h6 class="text-muted"><?php echo e(cleanLang(__('lang.tasks'))); ?></h6>
                        </td>
                        <td class="font-medium p-r-0 p-t-5 w-50 vt">
                            <span class="project_progress_hidden_text">30%</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="m-t-10 m-b-10">
        <hr>
    </div>
    <div class="card-body p-t-0 p-b-0">
        <!--[client details]-->
        <?php if(auth()->user()->is_team): ?>
        <div class="p-b-20">
            <h6><a href="/clients/<?php echo e($project->client_id); ?>"><?php echo e($project->client_company_name ?? $project->f_name); ?></a></h6>
            <div>
                <?php $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span data-toggle="tooltip" title="<?php echo e($contact->first_name); ?> <?php echo e($contact->last_name); ?>"><img
                        src="<?php echo e(getUsersAvatar($contact->avatar_directory, $contact->avatar_filename)); ?>" alt="user"
                        class="img-circle avatar-xsmall"></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        <!--assigned-->
        <div class="row">
            <div class="col-sm-6">
                <div class="panel-label p-b-3"><?php echo e(cleanLang(__('lang.assigned'))); ?></div>
                <div>
                    <div class="m-b-10">
                    <?php $__currentLoopData = $project->assigned->unique('first_name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                     
                            <span data-toggle="tooltip" title="<?php echo e($team->first_name); ?> <?php echo e($team->last_name); ?>"><img
                                    src="<?php echo e(getUsersAvatar($team->avatar_directory, $team->avatar_filename)); ?>" alt="user"
                                    class="img-circle avatar-xsmall"></span>
                     
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                    <?php if($project->assigned->count() == 0): ?>
                    ---
                    <?php endif; ?>
                </div>
            </div>

            <!--project manager-->
            <div class="col-sm-6">
                <?php if(auth()->user()->is_team): ?>
                <div class="panel-label p-b-3"><?php echo e(cleanLang(__('lang.project_manager'))); ?></div>
                <div>
                    <?php $__currentLoopData = $project->managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span data-toggle="tooltip" title="<?php echo e($team->first_name); ?> <?php echo e($team->last_name); ?>"><img
                            src="<?php echo e(getUsersAvatar($team->avatar_directory, $team->avatar_filename)); ?>" alt="user"
                            class="img-circle avatar-xsmall"></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($project->managers()->count() == 0): ?>
                    ---
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="m-t-10 m-b-10">
        <hr>
    </div>
    <!--dates-->
    <div class="card-body p-t-0 p-b-0">
        <div class="row">
            <div class="col-sm-6">
                <div>
                    <div class="panel-label p-b-3"><?php echo e(cleanLang(__('lang.start_date'))); ?></div>
                    <div><?php echo e(runtimeDate($project->project_date_start)); ?></div>
                </div>

                <div class="m-t-20">
                    <div class="panel-label p-b-3"><?php echo e(cleanLang(__('lang.category'))); ?></div>
                    <div><?php echo e($project->category_name); ?></div>
                </div>
                <div class="m-t-20">
                    <div class="panel-label p-b-3"><?php echo e(cleanLang(__('lang.quotation_no'))); ?></div>
                    <div><?php echo e($project->quotation_no); ?></div>
                </div>

            </div>
            <div class="col-sm-6">
                <div>
                    <div class="panel-label p-b-3"><?php echo e(cleanLang(__('lang.due_date'))); ?></div>
                    <div><?php echo e(runtimeDate($project->project_date_due)); ?></div>
                </div>
                <div class="m-t-20">
                    <div class="panel-label p-b-3"><?php echo e(cleanLang(__('lang.status'))); ?></div>
                    <div><span
                            class="label <?php echo e(runtimeProjectStatusColors($project->project_status, 'label')); ?>"><?php echo e(runtimeLang($project->project_status)); ?></span>
                    </div>
                </div>
                <div class="m-t-20">
                    <div class="panel-label p-b-3">Project Sites</div>
                    <div class="project-sites">
                        <?php 
                            $project_address = DB::table('project_addresses')->where('project_id', $project->project_id)->get();
                        ?>
                        <?php if($project_address->isNotEmpty()): ?>
                        <?php
                            $grouped_sites = $project_address->groupBy('project_site');
                        ?>
                        <?php $__currentLoopData = $grouped_sites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $site_name => $addresses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="project-site-group m-b-15">
                            <h6 class="font-weight-bold"><?php echo e(__('Site')); ?>: <?php echo e($site_name); ?></h6>
                            <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="project-site-item m-b-10">
                                <p class="m-b-5"><i class="mdi mdi-map-marker"></i> <?php echo e($address->project_site); ?></p>
                                <p class="text-muted"><i class="mdi mdi-home"></i> <?php echo e($address->project_address); ?></p>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <hr class="m-t-10 m-b-10">
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <p class="text-muted">---</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="m-t-20">
                    <div class="panel-label p-b-3">Project Code</div>
                    <div><?php echo e($project->project_code); ?></div>
                </div>
            </div>

        </div>
    </div>
    <!-- <div class="m-t-10 m-b-10">
        <hr>
    </div> -->
    <!--billing details-->
    <!-- <?php if(config('visibility.project_billing_summary')): ?> -->
    <!-- <div class="card-body p-t-0 p-b-0">
        <div class="row">
            <div class="col-sm-6">
                <div>
                    <div class="panel-label p-b-3"><?php echo e(cleanLang(__('lang.billing_type'))); ?></div>
                    <?php if($project->project_date_start == 'hourly'): ?>
                    <div><?php echo e(cleanLang(__('lang.hourly'))); ?></div>
                    <?php else: ?>
                    <div><?php echo e(cleanLang(__('lang.fixed_fee'))); ?></div>
                    <?php endif; ?>
                </div>

                <div class="m-t-20">
                    <div class="panel-label p-b-3"><?php echo e(cleanLang(__('lang.estimated_hours'))); ?></div>
                    <div><?php echo e($project->project_billing_estimated_hours); ?> <?php echo e(strtolower(__('lang.hrs'))); ?>

                    </div>
                </div>

            </div>
            <div class="col-sm-6">
                <div>
                    <div class="panel-label p-b-3"><?php echo e(cleanLang(__('lang.rate'))); ?></div>
                    <div><?php echo e(runtimeMoneyFormat($project->project_billing_rate)); ?></div>
                </div>
                <div class="m-t-20">
                    <div class="panel-label p-b-3"><?php echo e(cleanLang(__('lang.time_spent'))); ?></div>
                    <div><?php echo e($payload['time_logged']); ?></div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- <?php endif; ?> -->

    <!-- <?php if(config('settings.project_permissions_view_invoices')): ?> -->
    <!--INVOICES-->
    <!-- <div class="m-t-10 m-b-10">
        <hr>
    </div>
    <div class="card-body p-t-0 p-b-0">
        <div>
            <table class="table no-border m-b-0">
                <tbody>
                    <tr>
                        <td class="p-l-0 p-t-5 w-50"><?php echo e(cleanLang(__('lang.all_invoices'))); ?></td>
                        <td class="font-medium p-r-0 p-t-5">
                            <?php echo e(runtimeMoneyFormat($project->sum_invoices_all)); ?>

                            <div class="progress">
                                <?php if($project->sum_invoices_all > 0): ?>
                                <div class="progress-bar bg-info w-100 h-px-3" role="progressbar">
                                    <?php else: ?>
                                    <div class="progress-bar bg-info w-0 h-px-3" role="progressbar">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-l-0 p-t-5"><?php echo e(cleanLang(__('lang.paid_invoices'))); ?></td>
                        <td class="font-medium p-r-0 p-t-5">
                            <?php echo e(runtimeMoneyFormat($project->sum_invoices_paid)); ?>

                            <div class="progress">
                                <div class="progress-bar bg-success <?php echo e(runtimeProjectInvoicesBars($project->sum_invoices_all, $project->sum_invoices_paid)); ?>" role="progressbar"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-l-0 p-t-5"><?php echo e(cleanLang(__('lang.due_invoices'))); ?></td>
                        <td class="font-medium p-r-0 p-t-5">
                            <?php echo e(runtimeMoneyFormat($project->sum_invoices_due)); ?>

                            <div class="progress">
                                <div class="progress-bar bg-warning <?php echo e(runtimeProjectInvoicesBars($project->sum_invoices_all, $project->sum_invoices_due)); ?>" role="progressbar"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-l-0 p-t-5"><?php echo e(cleanLang(__('lang.overdue_invoices'))); ?></td>
                        <td class="font-medium p-r-0 p-t-5">
                            <?php echo e(runtimeMoneyFormat($project->sum_invoices_overdue)); ?>

                            <div class="progress">
                                <div class="progress-bar bg-danger <?php echo e(runtimeProjectInvoicesBars($project->sum_invoices_all, $project->sum_invoices_overdue)); ?>" role="progressbar"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div> -->
    <!-- <?php endif; ?> -->

    <div class="d-none last-line">
        <hr>
    </div>
</div>
<!-- Column --><?php /**PATH E:\xampp\htdocs\css\orion\application\resources\views/pages/project/components/misc/details.blade.php ENDPATH**/ ?>