<div class="form-group row">
    <label for="example-month-input" class="col-12 col-form-label text-left"><?php echo e(cleanLang(__('lang.status'))); ?></label>
    <div class="col-sm-12">
        <select class="select2-basic form-control form-control-sm" id="bill_status" name="bill_status">
            <option value="draft" <?php echo e(runtimePreselected('draft', $estimate->bill_status)); ?>><?php echo e(cleanLang(__('lang.draft'))); ?></option>
            <option value="new" <?php echo e(runtimePreselected('new', $estimate->bill_status)); ?>>Waiting for Customer Approval</option>
            <option value="waiting_for_approval" <?php echo e(runtimePreselected('waiting_for_approval', $estimate->bill_status)); ?>>Waiting for Management Approval</option>
            <option value="accepted" <?php echo e(runtimePreselected('accepted', $estimate->bill_status)); ?>><?php echo e("Approval"); ?></option>
            <option value="declined" <?php echo e(runtimePreselected('declined', $estimate->bill_status)); ?>><?php echo e(cleanLang(__('lang.declined'))); ?></option>
            <option value="revised" <?php echo e(runtimePreselected('revised', $estimate->bill_status)); ?>><?php echo e(cleanLang(__('lang.revised'))); ?></option>
            <option value="expired" <?php echo e(runtimePreselected('expired', $estimate->bill_status)); ?>><?php echo e(cleanLang(__('lang.expired'))); ?></option>
        </select>
    </div>
  
</div>
<?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/estimates/components/actions/change-status.blade.php ENDPATH**/ ?>