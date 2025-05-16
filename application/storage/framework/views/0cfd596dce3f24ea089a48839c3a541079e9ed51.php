<table class="table table-bordered border-secondary">
    <thead>
        <tr>
            <th rowspan="3">Item</th>
            <th rowspan="3">Description</th>
            <th rowspan="3">Unit</th>
            <th rowspan="3">Qty</th>
            <th colspan="6">Rate</th>
            <th rowspan="3">Total($)</th>
            <th rowspan="3">AMOUNT</th>
        </tr>
        <tr>
            <th rowspan="2">Labour</th>
            <th rowspan="2">Material</th>
            <th rowspan="2">Misc</th>
            <th colspan="2">Wastage</th>
            <th rowspan="2">S/C($)</th>
        </tr>
        <tr>
            <th>%</th>
            <th>$</th>
        </tr>
        <tr>
            <th class="text-start"></th>
            <th class="text-start">BILL NO. 5 - PROPOSED ELECTRICAL & ACMV INSTALLATION</th>
            <th class="text-start">mth</th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
        </tr>
    </thead>
    
    <tbody id="elec-table-body">
        <?php if(config('visibility.bill_mode') == 'viewing'): ?>
            <?php $__currentLoopData = $quotation_templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($data->template_id == 4): ?>
            <tr class="fw-bold">
                <td><?php echo e($loop->iteration); ?></td>
                <td><?php echo e($data->description); ?></td>
                <td><?php echo e($data->unit); ?></td>
                <td><?php echo e($data->qty); ?></td>
                <td><?php echo e($data->labour); ?></td>
                <td><?php echo e($data->material); ?></td>
                <td><?php echo e($data->misc); ?></td>
                <td><?php echo e($data->wastage_percent); ?></td>
                <td><?php echo e($data->wastage_amount); ?></td>
                <td><?php echo e($data->sc); ?></td>
                <td><?php echo e($data->total); ?></td>
                <td><?php echo e($data->amount); ?></td>
            </tr>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php elseif(config('visibility.bill_mode') == 'editing'): ?>
        <?php $__currentLoopData = $quotation_templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($data->template_id == 4): ?>
        <tr class="fw-bold">
            <td>
                <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm" data-id="<?php echo e($data->id); ?>" data-url="<?php echo e(route('deletedata', ['id' => $data->id])); ?>">
                    <i class="sl-icon-trash"></i>
                </button>
            </td>
            <td><input type="text" name="description[]" value="<?php echo e($data->description); ?>" class="form-control description-input" /></td>
            <td><select class="form-control" name="unit[]"><option value="sum" <?php echo e($data->unit == 'sum' ? 'selected' : ''); ?>>Sum</option><option value="mth" <?php echo e($data->unit == 'mth' ? 'selected' : ''); ?>>mth</option></select></td>
            <td><input type="text" name="qty[]" value="<?php echo e($data->qty); ?>" class="form-control qty-input" min="1" style="width: 70px;"/></td>
            <td><input type="text" name="labour[]" value="<?php echo e($data->labour); ?>" class="form-control rate-input" style="width: 70px;" /></td>
            <td><input type="text" name="material[]" value="<?php echo e($data->material); ?>" class="form-control rate-input" style="width: 70px;" /></td>
            <td><input type="text" name="misc[]" value="<?php echo e($data->misc); ?>" class="form-control rate-input" style="width: 70px;"/></td>
            <td><input type="text" name="wastage_percent[]" value="<?php echo e($data->wastage_percent); ?>" class="form-control rate-input" style="width: 70px;" /></td>
            <td><input type="text" name="wastage_amount[]" value="<?php echo e($data->wastage_amount); ?>" class="form-control rate-input" style="width: 70px;"/></td>
            <td><input type="text" name="sc[]" value="<?php echo e($data->sc); ?>" class="form-control rate-input" style="width: 70px;"/></td>
            <td><input type="text" name="total[]" value="<?php echo e($data->total); ?>" class="form-control total-input" style="width: 70px;" readonly /></td>
            <td><input type="text" name="amount[]" value="<?php echo e($data->amount); ?>" class="form-control quotation-amount-input" style="width: 70px;" readonly /></td>
            <input type="hidden" name="quotation_no[]" value="<?php echo e($page['crumbs'][2]); ?>" />
            <input type="hidden" name="template_id[]" value="4" />
            <input type="hidden" name="id[]" value="<?php echo e($data->id); ?>" />
        </tr>
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

    </tbody>
</table>

<?php if(config('visibility.bill_mode') == 'editing'): ?>
    <button type="button" id="elec_new_blank_line" class="btn btn-secondary btn-rounded btn-sm btn-rounded-icon">
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
       document.getElementById('elec_new_blank_line').addEventListener('click', function() {
        const tableBody = document.getElementById('elec-table-body');

        const elecnewRow = document.createElement('tr');
        elecnewRow.innerHTML = `
            <td>
                <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm">
                    <i class="sl-icon-trash"></i>
                </button>
            </td>
            <td><input type="text" name="description[]" value="" style="width: 100%;" class="form-control description-input" /></td>
            <td>
                <select class="form-control" name="unit[]">
                    <option value="sum">Sum</option>
                    <option value="mth">mth</option>
                </select>
            </td>
            <td><input type="text" name="qty[]" value="" class="form-control qty-input" min="1" style="width: 70px;"/></td>
            <td><input type="text" name="labour[]" value="" class="form-control rate-input" style="width: 70px;" /></td>
            <td><input type="text" name="material[]" value="" class="form-control rate-input" style="width: 70px;" /></td>
            <td><input type="text" name="misc[]" value="" class="form-control rate-input" style="width: 70px;" /></td>
            <td><input type="text" name="wastage_percent[]" value="" class="form-control rate-input" style="width: 70px;" /></td>
            <td><input type="text" name="wastage_amount[]" value="" class="form-control rate-input" style="width: 70px;" /></td>
            <td><input type="text" name="sc[]" value="" class="form-control rate-input" style="width: 70px;" /></td>
            <td><input type="text" name="total[]" value="" class="form-control total-input" style="width: 70px;" readonly /></td>
            <td><input type="text" name="amount[]" value="" class="form-control quotation-amount-input" style="width: 70px;" readonly /></td>
            <input type="hidden" name="quotation_no[]" class="form-control" value="<?php echo e($page['crumbs'][2]); ?>">
            <input type="hidden" name="template_id[]" class="form-control" value="4">
            <input type="hidden" name="id[]" value="">
        `;

        tableBody.appendChild(elecnewRow);

        // Add event listeners to the new row's inputs
        elecnewRow.querySelectorAll('.rate-input, .qty-input').forEach(input => {
            input.addEventListener('input', updateTotals);
        });

        elecnewRow.querySelector('.delete-row-btn').addEventListener('click', function() {
            elecnewRow.remove();
        });
    });
});


</script>
<?php /**PATH C:\xampp\htdocs\orion-ci-laravel\application\resources\views/pages/bill/components/elements/templates/elec_acme.blade.php ENDPATH**/ ?>