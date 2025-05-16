<div class="row">
    <div class="col-lg-12">
        <!--title-->
        <div class="form-group row">
            <label class="col-12 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.role_name'))); ?></label>
            <div class="col-12">
                <input type="text" class="form-control form-control-sm" id="role_name" name="role_name"
                    value="<?php echo e($role->role_name ?? ''); ?>">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><?php echo e(cleanLang(__('lang.resource'))); ?></th>
                        <th id="fx-settings-roles-th-1">
                            <?php echo e(cleanLang(__('lang.permissions'))); ?>

                            <span class="align-middle text-themecontrast font-16" data-toggle="tooltip"
                                title="<?php echo e(cleanLang(__('lang.edit_delete_permission_only_for_user'))); ?>" data-placement="top"><i class="ti-info-alt"></i></span>
                        </th>
                        <th class="text-center"><?php echo e(cleanLang(__('lang.global'))); ?> <span
                                class="align-middle text-themecontrast font-16" data-toggle="tooltip"
                                title="<?php echo e(cleanLang(__('lang.roles_scope_info'))); ?>" data-placement="top"><i
                                    class="ti-info-alt"></i></span></th>
                    </tr>
                </thead>
                <tbody>

                    <!--projects-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.projects'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_projects" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_projects ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_projects ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_projects ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_projects ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_projects_scope" name="role_projects_scope"
                                        class="filled-in chk-col-light-blue"
                                        <?php echo e(runtimePrechecked($role->role_projects_scope ?? '')); ?>>
                                    <label for="role_projects_scope"></label>
                                </div>
                            </div>
                        </td>
                    </tr>


                    <!--project billing-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.project_billing_info'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_projects_billing" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_projects_billing ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_projects_billing ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_projects_billing ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_projects_scope" name="role_projects_billing_scope"
                                        class="filled-in chk-col-light-blue" checked disabled>
                                    <label for="role_projects_billing_scope"></label>
                                </div>
                            </div>
                        </td>
                    </tr>



                    <!--leads-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.leads'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_leads" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_leads ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_leads ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_leads ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_leads ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_leads_scope" name="role_leads_scope"
                                        class="filled-in chk-col-light-blue"
                                        <?php echo e(runtimePrechecked($role->role_leads_scope ?? '')); ?>>
                                    <label for="role_leads_scope"></label>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!--tasks-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.tasks'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_tasks" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_tasks ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_tasks ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_tasks ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_tasks ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_tasks_scope" name="role_tasks_scope"
                                        class="filled-in chk-col-light-blue"
                                        <?php echo e(runtimePrechecked($role->role_tasks_scope ?? '')); ?>>
                                    <label for="role_tasks_scope"></label>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!--expenses-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.expenses'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_expenses" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_expenses ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_expenses ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_expenses ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_expenses ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_expenses_scope" name="role_expenses_scope"
                                        class="filled-in chk-col-light-blue"
                                        <?php echo e(runtimePrechecked($role->role_expenses_scope ?? '')); ?>>
                                    <label for="role_expenses_scope"></label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo e("Billing Address"); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_billing_address" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_billing_address ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_billing_address ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_billing_address ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_billing_address ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0  w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_billing_address_scope" name="role_billing_address_scope"
                                        class="filled-in chk-col-light-blue" <?php echo e(runtimePrechecked($role->role_billing_address_scope ?? '')); ?>>
                                    <label for="role_billing_address_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo e("Delivery Address"); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_delivery_address" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_delivery_address ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_delivery_address ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_delivery_address ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_delivery_address ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0  w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_delivery_address_scope" name="role_delivery_address_scope"
                                        class="filled-in chk-col-light-blue" <?php echo e(runtimePrechecked($role->role_delivery_address_scope ?? '')); ?>>
                                    <label for="role_delivery_address_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!--timesheets-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.time_sheets'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_timesheets" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_timesheets ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_timesheets ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_timesheets ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_timesheets_scope" name="role_timesheets_scope"
                                        class="filled-in chk-col-light-blue"
                                        <?php echo e(runtimePrechecked($role->role_timesheets_scope ?? '')); ?>>
                                    <label for="role_timesheets_scope"></label>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!--Invoices-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.invoices'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_invoices" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_invoices ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_invoices ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_invoices ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_invoices ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_invoices_scope" name="role_invoices_scope"
                                        class="filled-in chk-col-light-blue" <?php echo e(runtimePrechecked($role->role_invoices_scope ?? '')); ?>>
                                    <label for="role_invoices_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo e("Inventary"); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_inventary" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_inventary ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_inventary ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_inventary ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_inventary ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_inventary_scope" name="role_inventary_scope"
                                        class="filled-in chk-col-light-blue" <?php echo e(runtimePrechecked($role->role_inventary_scope ?? '')); ?>>
                                    <label for="role_inventary_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo e("Purchase Requisiton"); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_purchase_requisiton" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_purchase_requisiton ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_purchase_requisiton ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_purchase_requisiton ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_purchase_requisiton ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_purchase_requisiton_scope" name="role_purchase_requisiton_scope"
                                        class="filled-in chk-col-light-blue" <?php echo e(runtimePrechecked($role->role_purchase_requisiton_scope ?? '')); ?>>
                                    <label for="role_purchase_requisiton_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo e("Budget"); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_budget" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_budget ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_budget ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_budget ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_budget ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_budget_scope" name="role_budget_scope"
                                        class="filled-in chk-col-light-blue" <?php echo e(runtimePrechecked($role->role_purchase_requisiton_scope ?? '')); ?>>
                                    <label for="role_budget_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo e("Payment"); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_payment" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_payments ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_payments ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_payments ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_payments ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_payment_scope" name="role_payment_scope"
                                        class="filled-in chk-col-light-blue" <?php echo e(runtimePrechecked($role->role_purchase_requisiton_scope ?? '')); ?>>
                                    <label for="role_payment_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo e("Variation order"); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_variation_order" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_variation_order ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_variation_order ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_variation_order ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_variation_order ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_variation_order_scope" name="role_variation_order_scope"
                                        class="filled-in chk-col-light-blue" <?php echo e(runtimePrechecked($role->role_purchase_requisiton_scope ?? '')); ?>>
                                    <label for="role_variation_order_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>


                    <!--Products-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.products'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_items" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_items ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_items ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_items ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_items ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_items_scope" name="role_items_scope"
                                        class="filled-in chk-col-light-blue" <?php echo e(runtimePrechecked($role->role_items_scope ?? '')); ?>>
                                    <label for="role_items_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!--Estimates-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.estimates'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_estimates" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_estimates ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_estimates ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_estimates ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_estimates ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_estimates_scope" name="role_estimates_scope"
                                        class="filled-in chk-col-light-blue" <?php echo e(runtimePrechecked($role->role_estimates_scope ?? '')); ?>>
                                    <label for="role_estimates_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!--Clients-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.clients'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_clients" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_clients ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_clients ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_clients ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_clients ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_clients_scope" name="role_clients_scope"
                                        class="filled-in chk-col-light-blue" <?php echo e(runtimePrechecked($role->role_clients_scope ?? '')); ?>>
                                    <label for="role_clients_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!--Contacts-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.contacts'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_contacts" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_contacts ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_contacts ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_contacts ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_contacts ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0  w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_contacts_scope" name="role_contacts_scope"
                                        class="filled-in chk-col-light-blue" <?php echo e(runtimePrechecked($role->role_contacts_scope ?? '')); ?>>
                                    <label for="role_contacts_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!--tickets-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.tickets'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_tickets" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_tickets ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_tickets ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_tickets ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_tickets ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center  p-t-5">
                                    <input type="checkbox" id="role_tickets_scope" name="role_tickets_scope"
                                        class="filled-in chk-col-light-blue" disabled checked="checked">
                                    <label for="role_tickets_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!--knowledge base-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.knowledgebase'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm"
                                        name="role_knowledgebase" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_knowledgebase ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_knowledgebase ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_knowledgebase ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_knowledgebase ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center  p-t-5">
                                    <input type="checkbox" id="role_knowledgebase_scope" name="role_knowledgebase_scope"
                                        class="filled-in chk-col-light-blue" disabled checked="checked">
                                    <label for="role_knowledgebase_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!--reports-->
                    <!--[UPCOMING]-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.reports'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_reports" data-allow-clear="false">
                                        <option value="0" <?php echo e(runtimePreselected($role->role_reports ?? '', 0)); ?>>
                                            <?php echo e(cleanLang(__('lang.none'))); ?>

                                        </option>
                                        <option value="1" <?php echo e(runtimePreselected($role->role_reports ?? '', 1)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?>

                                        </option>
                                        <option value="2" <?php echo e(runtimePreselected($role->role_reports ?? '', 2)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?>

                                        </option>
                                        <option value="3" <?php echo e(runtimePreselected($role->role_reports ?? '', 3)); ?>>
                                            <?php echo e(cleanLang(__('lang.view'))); ?> + <?php echo e(cleanLang(__('lang.add'))); ?> + <?php echo e(cleanLang(__('lang.edit'))); ?> +
                                            <?php echo e(cleanLang(__('lang.delete'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center  p-t-5">
                                    <input type="checkbox" id="role_reports_scope" name="role_reports_scope"
                                        class="filled-in chk-col-light-blue" <?php echo e(runtimePrechecked($role->role_reports_scope ?? '')); ?>>
                                    <label for="role_reports_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!--assigned projects-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.assign_projects'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm"
                                        name="role_assign_projects" data-allow-clear="false">
                                        <option value=no" <?php echo e(runtimePreselected($role->role_assign_projects ?? '', 'no')); ?>>
                                            <?php echo e(cleanLang(__('lang.no'))); ?>

                                        </option>
                                        <option value="yes"
                                            <?php echo e(runtimePreselected($role->role_assign_projects ?? '', 'yes')); ?>>
                                            <?php echo e(cleanLang(__('lang.yes'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_assign_projects_scope"
                                        name="role_assign_projects_scope" class="filled-in chk-col-light-blue" disabled
                                        checked="checked">
                                    <label for="role_assign_projects_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!--assign leads-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.assign_leads'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_assign_leads" data-allow-clear="false">
                                        <option value="no" <?php echo e(runtimePreselected($role->role_assign_leads ?? '', 'no')); ?>>
                                            <?php echo e(cleanLang(__('lang.no'))); ?>

                                        </option>
                                        <option value="yes" <?php echo e(runtimePreselected($role->role_assign_leads ?? '', 'yes')); ?>>
                                            <?php echo e(cleanLang(__('lang.yes'))); ?>

                                        </option>

                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_assign_leads_scope" name="role_assign_leads_scope"
                                        class="filled-in chk-col-light-blue" disabled checked="checked">
                                    <label for="role_assign_leads_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!--assign tasks-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.assign_tasks'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm" name="role_assign_tasks" data-allow-clear="false">
                                        <option value="no" <?php echo e(runtimePreselected($role->role_assign_tasks ?? '', 'no')); ?>>
                                            <?php echo e(cleanLang(__('lang.no'))); ?>

                                        </option>
                                        <option value="yes" <?php echo e(runtimePreselected($role->role_assign_tasks ?? '', 'yes')); ?>>
                                            <?php echo e(cleanLang(__('lang.yes'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_assign_tasks_scope" name="role_assign_tasks_scope"
                                        class="filled-in chk-col-light-blue" disabled checked="checked">
                                    <label for="role_assign_tasks_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>


                    <!--set project permissions-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.set_project_permissions'))); ?> <span class="align-middle text-themecontrast font-16"
                                data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.all_setting_permissions_when_adding_project'))); ?>"
                                data-placement="top"><i class="ti-info-alt"></i></span></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm"
                                        name="role_set_project_permissions" data-allow-clear="false">
                                        <option value=no"
                                            <?php echo e(runtimePreselected($role->role_set_project_permissions ?? '', 'no')); ?>>
                                            <?php echo e(cleanLang(__('lang.no'))); ?>

                                        </option>
                                        <option value="yes"
                                            <?php echo e(runtimePreselected($role->role_set_project_permissions ?? '', 'yes')); ?>>
                                            <?php echo e(cleanLang(__('lang.yes'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_set_project_permissions_scope"
                                        name="role_set_project_permissions_scope" class="filled-in chk-col-light-blue"
                                        disabled checked="checked">
                                    <label for="role_set_project_permissions_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>


                    <!--manage categories - knowledgebase-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.manage_knowledgebase'))); ?></td>
                        <td>
                            <div class="form-group row m-0">
                                <div class="col-12 text-center">
                                    <select class="select2-basic form-control form-control-sm"
                                        name="role_manage_knowledgebase_categories" data-allow-clear="false">
                                        <option value="no"
                                            <?php echo e(runtimePreselected($role->role_manage_knowledgebase_categories ?? '', 'no')); ?>>
                                            <?php echo e(cleanLang(__('lang.no'))); ?>

                                        </option>
                                        <option value="yes"
                                            <?php echo e(runtimePreselected($role->role_manage_knowledgebase_categories ?? '', 'yes')); ?>>
                                            <?php echo e(cleanLang(__('lang.yes'))); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-group-checkbox row m-0 w-98">
                                <div class="col-12 text-center p-t-5">
                                    <input type="checkbox" id="role_manage_knowledgebase_categories_scope"
                                        name="role_manage_knowledgebase_categories_scope"
                                        class="filled-in chk-col-light-blue" disabled checked="checked">
                                    <label for="role_manage_knowledgebase_categories_scope" data-toggle="tooltip"
                                        title="<?php echo e(cleanLang(__('lang.can_only_be_set_as_global'))); ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>


                </tbody>
            </table>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/settings/sections/roles/modals/add-edit-inc.blade.php ENDPATH**/ ?>