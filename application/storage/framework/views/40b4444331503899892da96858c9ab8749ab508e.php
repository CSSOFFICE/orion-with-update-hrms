
<table class="table table-bordered border-secondary">
    <thead>
        <tr>
            <th>Item</th>
            <th>Description</th>
            <th>Unit</th>
            <th>Qty</th>
            <th>AMOUNT</th>
        </tr>
        <tr>
            <th class="text-start"></th>
            <th class="text-start">BILL NO. 4 - PROPOSED PLUMBING & SANITARY WORKS</th>
            <th class="text-start"></th>
            <th class="text-start"></th>
        </tr>
    </thead>
    
    <tbody id="external-table-body">
        <?php if(config('visibility.bill_mode') == 'viewing'): ?>
            <?php $__currentLoopData = $quotation_templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($data->template_id == 6): ?>
            <tr class="fw-bold">
                <td><?php echo e($loop->iteration); ?></td>
                <td><?php echo e($data->description); ?></td>
                <td><?php echo e($data->unit); ?></td>
                <td><?php echo e($data->qty); ?></td>
                <td><?php echo e($data->amount); ?></td>
            </tr>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php elseif(config('visibility.bill_mode') == 'editing'): ?>
        <?php $__currentLoopData = $quotation_templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($data->template_id == 6): ?>
        <tr class="fw-bold">
            <td>
                <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm" data-id="<?php echo e($data->id); ?>" data-url="<?php echo e(route('deletedata', ['id' => $data->id])); ?>">
                    <i class="sl-icon-trash"></i>
                </button>
            </td>
            <td><input type="text" name="description[]" value="<?php echo e($data->description); ?>" class="form-control description-input" /></td>
            <td><select class="form-control" name="unit[]"><option value="sum" <?php echo e($data->unit == 'sum' ? 'selected' : ''); ?>>Sum</option><option value="mth" <?php echo e($data->unit == 'mth' ? 'selected' : ''); ?>>mth</option></select></td>
            <td><input type="text" name="qty[]" value="<?php echo e($data->qty); ?>" class="form-control qty-input" min="1" style="width: 70px;"/></td>
            <td><input type="text" name="amount[]" value="<?php echo e($data->amount); ?>" class="form-control quotation-amount-input" style="width: 70px;"/></td>
            <input type="hidden" name="quotation_no[]" value="<?php echo e($page['crumbs'][2]); ?>" />
            <input type="hidden" name="template_id[]" value="6" />
            <input type="hidden" name="id[]" value="<?php echo e($data->id); ?>" />
        </tr>
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

    </tbody>

</table>

<?php if(config('visibility.bill_mode') == 'editing'): ?>
<button type="button" id="external_new_blank_line" class="btn btn-secondary btn-rounded btn-sm btn-rounded-icon">
    <i class="mdi mdi-plus-circle-outline text-themecontrast"></i>
    <span><?php echo e(cleanLang(__('lang.new_blank_line'))); ?></span>
</button>
<?php endif; ?>
<script>
    // Function to update totals
    function updateTotals() {
        const row = this.closest('tr');
        const rateInputs = row.querySelectorAll('.rate-input');
        const qtyInput = row.querySelector('.qty-input');
        const totalInput = row.querySelector('.total-input');
        const amountInput = row.querySelector('.quotation-amount-input');

        let totalRate = 0;
        rateInputs.forEach(input => {
            totalRate += parseFloat(input.value) || 0;
        });

        const qty = parseFloat(qtyInput.value) || 0;
        const totalAmount = totalRate;
        const quotationAmount = totalRate * qty;

        totalInput.value = totalAmount.toFixed(2);
        amountInput.value = quotationAmount.toFixed(2);
    }

    // Add event listeners to existing rows
    document.querySelectorAll('.rate-input, .qty-input').forEach(input => {
        input.addEventListener('input', updateTotals);
    });

 document.addEventListener('DOMContentLoaded', function() {
       document.getElementById('external_new_blank_line').addEventListener('click', function() {
        const tableBody = document.getElementById('external-table-body');

        const externalnewRow = document.createElement('tr');
        externalnewRow.innerHTML = `
        <td>
            <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm">
                <i class="sl-icon-trash"></i>
            </button>
        </td>
        <td><input type="text" name="description[]" value="" style="width: 100%;" class="form-control description-input" /></td>
        <td><select class="form-control" name="unit[]"><option value="sum">Sum</option><option value="mth">mth</option></select></td>
        <td><input type="text" name="qty[]" value="" class="form-control qty-input" min="1" style="width: 70px;"/></td>
        <td><input type="text" name="amount[]" value="" class="form-control quotation-amount-input" style="width: 70px;" /></td>
        <input type="hidden" name="quotation_no[]" class="form-control" value="<?php echo e($page['crumbs'][2]); ?>">
        <input type="hidden" name="template_id[]" class="form-control" value="6">
        <input type="hidden" name="id[]" value="">
        `;

        tableBody.appendChild(externalnewRow);

        // Add event listeners to the new row's inputs
        externalnewRow.querySelectorAll('.rate-input, .qty-input').forEach(input => {
            input.addEventListener('input', updateTotals);
        });

        externalnewRow.querySelector('.delete-row-btn').addEventListener('click', function() {
            externalnewRow.remove();
        });
    });
});


</script>





<?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/bill/components/elements/templates/external_works.blade.php ENDPATH**/ ?>