<div class="card count-<?php echo e(@count($address)); ?>" id="address-table-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper">
            <?php if(@count($address) > 0): ?>
                <table id="contacts-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                    data-page-size="10">
                    <thead>
                        <tr>
                            <?php if(config('visibility.address_col_checkboxes')): ?>
                                <th class="list-checkbox-wrapper">
                                    <!--list checkbox-->
                                    <span class="list-checkboxes display-inline-block w-px-20">
                                        <input type="checkbox" id="listcheckbox-contacts" name="listcheckbox-contacts"
                                            class="listcheckbox-all filled-in chk-col-light-blue"
                                            data-actions-container-class="contacts-checkbox-actions-container"
                                            data-children-checkbox-class="listcheckbox-contacts">
                                        <label for="listcheckbox-contacts"></label>
                                    </span>
                                </th>
                            <?php endif; ?>
                            <th class="contacts_col_first_name"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_first_name" href="javascript:void(0)"
                                    data-url="<?php echo e(urlResource('/address?action=sort&orderby=first_name&sortorder=asc')); ?>"><?php echo e('ID'); ?><span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                            </th>

                            <th class="contacts_col_company"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_company_name" href="javascript:void(0)"
                                    data-url="<?php echo e(urlResource('/address?action=sort&orderby=company_name&sortorder=asc')); ?>"><?php echo e('Address'); ?><span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                            </th>
                            <th class="contacts_col_email"><a class="js-ajax-ux-request js-list-sorting" id="sort_email"
                                    href="javascript:void(0)"
                                    data-url="<?php echo e(urlResource('/address?action=sort&orderby=email&sortorder=asc')); ?>"><?php echo e('Unit Number'); ?><span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></th>
                            <th class="contacts_col_phone"><a class="js-ajax-ux-request js-list-sorting" id="sort_phone"
                                    href="javascript:void(0)"
                                    data-url="<?php echo e(urlResource('/address?action=sort&orderby=phone&sortorder=asc')); ?>"><?php echo e('Country'); ?><span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></th>

                            <th class="contacts_col_last_active"><a class="js-ajax-ux-request js-list-sorting"
                                    id="sort_last_seen" href="javascript:void(0)"
                                    data-url="<?php echo e(urlResource('/address?action=sort&orderby=last_seen&sortorder=asc')); ?>"><?php echo e('Postal Code'); ?><span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></th>
                            <th class="contacts_col_phone"><a class="js-ajax-ux-request js-list-sorting" id="sort_phone"
                                    href="javascript:void(0)"
                                    data-url="<?php echo e(urlResource('/address?action=sort&orderby=phone&sortorder=asc')); ?>"><?php echo e('D Address'); ?><span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></th>

                            <?php if(config('visibility.action_column')): ?>
                                <th class="contacts_col_action"><a
                                        href="javascript:void(0)"><?php echo e(cleanLang(__('lang.action'))); ?></a></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody id="address-td-container">
                        <!--ajax content here-->
                        <?php echo $__env->make('pages.address.components.table.ajax', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <!--ajax content here-->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="20">
                                <!--load more button-->
                                <?php echo $__env->make('misc.load-more-button', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <!--load more button-->
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <?php endif; ?> <?php if(@count($address) == 0): ?>
                    <!--nothing found-->
                    <?php echo $__env->make('notifications.no-results-found', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <!--nothing found-->
                <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/address/components/table/table.blade.php ENDPATH**/ ?>