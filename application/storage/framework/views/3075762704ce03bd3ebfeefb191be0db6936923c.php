<?php

use Illuminate\Support\Facades\DB;

$purchaseOrder = DB::table('purchase_order')->get();
?>
<div class="row" id="js-trigger-expenses" data-client-id="<?php echo e($expense->expense_clientid ?? ''); ?>" data-payload="<?php echo e(config('visibility.expense_modal_trigger_clients_project_list')); ?>">

    <div class="col-lg-12">
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label  required">Choose Category </label>
            <div class="col-sm-12 col-lg-9">
                <select class="form-control" class="form-control form-control-sm" name="category_id" data-plugin="select_hrm" id="category_id_prili">
                    <option>Choose Category</option>
                    <?php foreach ($quotation_category as $k => $purchase) { ?>
                        <option value="<?php echo $purchase->milestonecategory_id; ?>"><?php echo $purchase->milestonecategory_title; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label  required">Task </label>
            <div class="col-sm-12 col-lg-9">
                <select name="task_id" id="task_id" class="form-control" data-plugin="select_hrm">

                </select>
            </div>
        </div>
        <input type="hidden" name="new_invoice_no" id="new_invoice_no">
        <input type="hidden" name="new_invoice_amount" id="new_invoice_amount">
        <input type="hidden" value="<?php echo e(request('expenseresource_id')); ?>" name="expense_projectid" id="expense_projectid">

        <!--Purchase Order-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label  required">Purchase Order <span>*</span></label>
            <div class="col-sm-12 col-lg-9">
                <select class="form-control form-control-sm" id="porder_id" name="porder_id" data-plugin="select_hrm">
                    <option value="" selected>Select Option</option>
                    <?php $__currentLoopData = $purchaseOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $po): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($po->purchase_order_id); ?>" <?php echo e(runtimePreselected($expense->purchase_order_no ?? '', $po->purchase_order_id)); ?>><?php echo e(runtimeLang($po->porder_id)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Amount <span>*</span></label>
            <div class="col-sm-12 col-lg-9">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon" id="basic-addon2">$</span>
                    <input type="number" name="expense_amount" class="form-control form-control-sm ">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Petty Cash <span>*</span></label>
            <div class="col-sm-12 col-lg-9">
                <div class="input-group input-group-sm">
                    <input class="form-check-input" type="checkbox" name="petty_cash" id="exampleCheckbox" data-id="" data-client-id="">
                    <label class="form-check-label" for="exampleCheckbox"></label>
                </div>
            </div>
        </div>
        <!--
        <div id="div_expence">

            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Invoice No <span>*</span></label>
                <div class="col-sm-12 col-lg-9">
                    <input type="text" class="form-control form-control-sm invoice_no" name="invoice_no[]" onblur="add_data()">
                </div>
            </div>


            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Invoice Amount <span>*</span></label>
                <div class="col-sm-12 c}}ol-lg-9">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon" id="basic-addon2">$</span>
                        <input type="number" name="invoice_amount[]" class="form-control form-control-sm invoice_amount" aria-describedby="basic-addon2" onblur="add_data()">
                    </div>
                </div>
            </div>
        </div> -->

        <!-- <div class="form-group text-right">
            <button type="button" class="btn btn-primary" id="add_column_ex">Add Invoice</button>
        </div> -->

        <!--date-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.date'))); ?>*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm pickadate" autocomplete="off" name="expense_date" value="<?php echo e(date('d-m-Y')); ?>">
                <input class="mysql-date" type="hidden" name="expense_date" value="<?php echo e(date('d-m-Y')); ?>" id="date">
            </div>
        </div>

        <!--do no-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">DO No <span>*</span></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" name="do_no">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Invoice No <span>*</span></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" name="invoice" value="<?php echo e($expense->purchase_invoice_no ?? ''); ?>">
            </div>
        </div>


        <!--Attach Invoice-->
        

        <!--fileupload-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Attachment</label>
            <div class="col-12">
                <div class="dropzone dz-clickable text-center file-upload-box" id="fileupload_expense_receipt">
                    <div class="dz-default dz-message">
                        <div>
                            <h4><?php echo e(cleanLang(__('lang.drag_drop_file'))); ?></h4>
                        </div>
                        <div class="p-t-10"><small><?php echo e(cleanLang(__('lang.allowed_file_types'))); ?>: (jpg|png)</small></div>
                        <div class=""><small><?php echo e(cleanLang(__('lang.best_image_dimensions'))); ?>: (185px X 45px)</small></div>
                    </div>
                </div>
            </div>
        </div>

        <!--existing files-->
        <?php if(isset($page['section']) && $page['section'] == 'edit'): ?>
        <table class="table table-bordered">
            <tbody>
                <?php $__currentLoopData = $attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr id="expense_attachment_<?php echo e($attachment->attachment_id); ?>">
                    <td><?php echo e($attachment->attachment_filename); ?> </td>
                    <td class="w-px-40"> <button type="button" class="btn btn-danger btn-circle btn-sm confirm-action-danger" data-confirm-title="<?php echo e(cleanLang(__('lang.delete_item'))); ?>" data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>" active" data-ajax-type="DELETE" data-url="<?php echo e(url('/expenses/attachments/'.$attachment->attachment_uniqiueid)); ?>">
                            <i class="sl-icon-trash"></i>
                        </button></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php endif; ?>

    </div>

    <!--pass source-->
    <input type="hidden" name="source" value="<?php echo e(request('source')); ?>">
    <input type="hidden" name="ref" value="<?php echo e(request('ref')); ?>">

    <div class="row">
        <div class="col-12">
            <div><small><strong>* <?php echo e(cleanLang(__('lang.required'))); ?></strong></small></div>
        </div>
    </div>
</div>

<script>
    // console.log($("#div_expence").parents('#commonModalForm').get(0));

    function add_data() {
        var invoice_no_arr = [];
        var invoice_amount_arr = [];

        var inputs1 = $(".invoice_no");
        var inputs2 = $(".invoice_amount");

        for (var i = 0; i < inputs1.length; i++) {
            invoice_no_arr.push($(inputs1[i]).val());
        }

        for (var i = 0; i < inputs2.length; i++) {
            invoice_amount_arr.push($(inputs2[i]).val());
        }

        console.log(invoice_no_arr);
        console.log(invoice_amount_arr);

        $("#new_invoice_no").val(invoice_no_arr);
        $("#new_invoice_amount").val(invoice_amount_arr);
    }

    $(document).ready(function() {

        $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
        $('[data-plugin="select_hrm"]').select2({
            width: '100%'
        });

        $("#add_column_ex").on("click", function() {

            var html = `<div class="form-group row">
                            <label
                                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Invoice No <span>*</span></label>
                            <div class="col-sm-12 col-lg-9">
                                <input type="text" class="form-control form-control-sm invoice_no" name="invoice_no[]" onblur="add_data()">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Invoice Amount <span>*</span></label>
                            <div class="col-sm-12 col-lg-9">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon" id="basic-addon2">$</span>
                                    <input type="number" name="invoice_amount[]" class="form-control form-control-sm invoice_amount" aria-describedby="basic-addon2" onblur="add_data()">
                                </div>
                            </div>
                        </div>`;

            $("#div_expence").append(html);

        });

    });
    $(document).ready(function() {
        $("#category_id_prili").on("change", function() {

            let id = $(this).val();

            let jsArray = <?php echo json_encode($budgtrepo, 15, 512) ?>;





            jsArray = jsArray.data.filter((re) => re.task_cat_id == id);
            let op = `<option value="" selected>Task</option>`;
            op += jsArray.map(re => `<option value="${re.task_id}">${re.task_title}</option>`).join('');

            $("#task_id").html(op);



        })
    })
</script>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/expenses/components/modals/add-edit-inc.blade.php ENDPATH**/ ?>